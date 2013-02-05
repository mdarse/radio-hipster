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
use RH\UploadType;

// Homepage with search & upload forms (+ playlist + top songs)
$app->match('/', function (Request $request) use ($app) {
    // Playlist items
    $playlist = PlayItemQuery::create()->find();
    // Search form
    $form = $app['form.factory']->createBuilder('form')
            ->add('search', 'search')
            ->getForm();
    // Top songs
    $topSongs = SongQuery::create()
        ->orderByListenCount(Criteria::DESC)
        ->limit(10)
        ->find();
    // Upload form (in modal window)
    $uploadForm = $app['form.factory']->create(new UploadType());

    return $app['twig']->render('index.html', array(
        'songsPlaylist' => $playlist,
        'searchForm'    => $form->createView(),
        'uploadForm'    => $uploadForm->createView(),
        'topSongs'      => $topSongs
    ));
})
->bind('homepage')
;

//This part of the controller is used to controle the search module with ajax method
$app->match('/search', function (Request $request) use ($app){

    $form = $app['form.factory']->createBuilder('form')
            ->add('search', 'search')
            ->getForm();
    
    $form->bind($request);
    $data = $form->getData();
    

    
    if ('GET' == $request->getMethod() && $data['search'] != null) {
        $songs = SongQuery::create()
                ->findByPattern($data['search']);        
        $songsOnAlbum = SongQuery::create()
                ->findByAlbumNamePattern($data['search']);
        $songsByArtists = SongQuery::create()
                ->findByArtistNamePattern($data['search']);
        
        return $app['twig']->render('resultSearch.html.twig', array(
                'songs' => $songs,
                'songsOnAlbum' => $songsOnAlbum,
                'songsByArtists' => $songsByArtists,
            ));
        
    }
    
    return $app['twig']->render('resultSearch.html.twig', array());
})
->method('GET|POST')
->bind('search')
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
    $form = $app['form.factory']->create(new UploadType(), $song);
    $form->bind($request);
    if ($form->isValid()) {
        $song->save();
        $song->extractID3();

        return $app->json($song->toArray());

        // $app['session']->getFlashBag()->add('success','Your song has been uploaded');
        return new Response('{ "status": "success" }', 201);
    }
    return new Response('{ "status": "error" }', 400);
})
->method('POST')
->bind('upload')
;

$app->match('/songs', function (Request $request) use ($app) {
    $query = $request->query->get('q');
    $in = $request->query->get('in', 'song|artist|album');
    $locations = explode('|', $in);
    // Whitelist locations
    $locations = array_intersect($locations, array('song', 'artist', 'album'));

    $songs = SongQuery::create()->findByPatternInLocations($query, $locations);
    $songs = array_map(function(Song $song) {
        return $song->toArray();
    }, $songs);

    return $app->json($songs);
})
->method('GET')
->bind('search')
;
     


//This Part of the contoller is used to insert an item in the PlayList
$app->get('/insert/{id}', function ($id) use ($app) {

    $item = new \RH\Model\PlayItem();


    //TODO : Time is only a temporary solution. This will have to change.
    $item->setOrder(time());

    $song = RH\Model\SongQuery::create()->findPk($id);
    $song->setListenCount($song->getListenCount()+1);
    $song->save();  //<= Ne fonctionne pas

    $item->setSong($song);
    $item->save();

    
    //$app['session']->setFlash('successAdd','Your song <strong>' . $song->getName() . '</strong> has been added at the radio playlist! Enjoy :-) !');


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
