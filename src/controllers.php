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
    // Top songs
    $topSongs = SongQuery::create()
        ->orderByListenCount(Criteria::DESC)
        ->limit(10)
        ->find();
    // Upload form (in modal window)
    $uploadForm = $app['form.factory']->create(new UploadType());

    return $app['twig']->render('index.html', array(
        'upload_form'    => $uploadForm->createView(),
        'top_songs'      => $topSongs
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

    if (count($songs) === 0) {
        return new Response('[]', 200, array(
            'Content-Type' => 'text/javascript'
        ));
    }

    return $app->json($songs);
})
->method('GET')
->bind('songs')
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
