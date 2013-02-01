<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use RH\Model\Song;
use RH\Model\SongQuery;
use RH\Model\ArtistQuery;
use RH\Model\PlayItem;
use RH\Model\PlayItemQuery;
use RH\Playlist;




//This part of the controller is used to controle the homepage module. It contain a search module.
$app->match('/', function (Request $request) use ($app) {
    
    //Playlist gestion
    $songsPlaylist = \RH\Model\PlayItemQuery::create()
            ->find();
    
    
    //TODO : CHANGE!! Copy/Past search controller to change. 
    
    //Search gestion
    $form = $app['form.factory']->createBuilder('form')
            ->add('search', 'search')
            ->getForm();
    
    $form->bind($request);
    $data = $form->getData();
    
  
    $top = SongQuery::create()
            ->orderByListenCount(Criteria::DESC)
            ->limit(10)
            ->find();
            
            

        
    //Il there are something in the GET
    if ('GET' == $request->getMethod() && $data['search'] != null) {

        $songs = SongQuery::create()
                ->findByPattern($data['search']);        
        $songsOnAlbum = SongQuery::create()
                ->findByAlbumNamePattern($data['search']);
        $songsByArtists = SongQuery::create()
                ->findByArtistNamePattern($data['search']);

        
        // Display the form and the result
        return $app['twig']->render('index.html', array(
                    'songsPlaylist' => $songsPlaylist,
                    'form' => $form->createView(),
                    'songs' => $songs,
                    'songsOnAlbum' => $songsOnAlbum,
                    'songsByArtists' => $songsByArtists
                ));
    }

    return $app['twig']->render('index.html', array(
                    'songsPlaylist' => $songsPlaylist,
                    'form' => $form->createView(),
                    'top' => $top
                ));
})
->bind('homepage')
;

$app->get('/playlist', function (Request $request) use ($app) {
    $items = PlayItemQuery::create()->findAllByOrderAsArray();
    $baseUrl = $request->getSchemeAndHttpHost().$request->getBasePath();
    $playlist = new Playlist($items, $baseUrl);

    return $app->json($playlist->toArray());
})
->bind('playlist')
;

$app->get('/player', function (Request $request) use ($app) {

    return $app['twig']->render('player.html', array());
})
->bind('player')
;

//This part of the controller is used to controle the upload module
$app->match('/upload', function (Request $request) use ($app) {
    $song = new Song();

//    \RH\Model\SongQuery::create()
//        ->filterById(14, \Criteria::GREATER_THAN)
//        ->find()
//        ->delete();

    $form = $app['form.factory']->createBuilder('form', $song)
        ->add('name')
        ->add('file', 'file')
        ->getForm();

    if ('POST' == $request->getMethod()) {
        $form->bind($request);

        if ($form->isValid()) {
            $song->save();
            $song->extractID3();

            
            $app['session']->setFlash('successUpload','Your song has been uploaded');
            
            return $app->redirect($app->path('homepage'));
            //return $app['twig']->render('uploaded.html', array('song' => $song));
        }
    }

    return $app['twig']->render('uploadForm.twig', array('form' => $form->createView()));
})
->method('GET|POST')
->bind('upload')
;


     


//This Part of the contoller is used to insert an item in the PlayList
$app->get('/insert/{id}', function ($id) use ($app) {

    $item = new \RH\Model\PlayItem();


    //TODO : Time is only a temporary solution. This will have to change.
    $item->setOrder(time());

    $song = RH\Model\SongQuery::create()->findPk($id);
    $song->setListenCount($song->getListenCount()+1);
    var_dump($song);
    $song->save();

    $item->setSong($song);
    $item->save();
    
    $app['session']->setFlash('successAdd','Your song <strong>' . $song->getName() . '</strong> has been added at the radio playlist! Enjoy :-) !');

    //return $app->redirect($app['url_generator']->generate('search'));
    return $app->redirect($app->path('homepage'));
})
->bind('insert')
;


//This part of the controller is used to controle the error in the web site
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
