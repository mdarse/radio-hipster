<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use RH\Model\Song;


$app->match('/', function (Request $request) use ($app) {
    
    //Playlist gestion
    $songsPlaylist = \RH\Model\PlayItemQuery::create()
            ->find();
    
    
    //TODO : CHANGE!! Copy/Past search controller to change. 
    
    //Search gestion
    $form = $app['form.factory']->createBuilder('form')
            ->add('search')
            ->getForm();
    
    $form->bind($request);
    $data = $form->getData();

        
    //Il there are something in the GET
    if ('GET' == $request->getMethod() && $data['search'] != null) {
        $songs = RH\Model\SongQuery::create()
                ->filterByName('%' . $data['search'] . '%')
                ->find();

        
        // Display the form and the result
        return $app['twig']->render('index.html', array(
                    'songsPlaylist' => $songsPlaylist,
                    'form' => $form->createView(),
                    'songs' => $songs
                ));
    }

    return $app['twig']->render('index.html', array(
                    'songsPlaylist' => $songsPlaylist,
                    'form' => $form->createView()
                ));
})
->bind('homepage')
;

$app->match('/upload', function (Request $request) use ($app) {
    $song = new Song();

    \RH\Model\SongQuery::create()
        ->filterById(14, \Criteria::GREATER_THAN)
        ->find()
        ->delete();

    $form = $app['form.factory']->createBuilder('form', $song)
        ->add('name')
        ->add('file', 'file')
        ->getForm();

    if ('POST' == $request->getMethod()) {
        $form->bind($request);

        if ($form->isValid()) {
            $song->save();

            return $app['twig']->render('uploaded.html', array('song' => $song));
        }
    }

    return $app['twig']->render('upload.html', array('form' => $form->createView()));
})
->method('GET|POST')
->bind('upload')
;


//This part of the controller is used to controle the search module
$app->match('/search', function (Request $request) use ($app) {
    $form = $app['form.factory']->createBuilder('form')
            ->add('search')
            ->getForm();
    
    $form->bind($request);
    $data = $form->getData();

        
    //Il there are something in the GET
    if ('GET' == $request->getMethod() && $data['search'] != null) {
        $songs = RH\Model\SongQuery::create()
                ->filterByName('%' . $data['search'] . '%')
                ->find();

        
        // Display the form and the result
        return $app['twig']->render('search.html', array(
                    'form' => $form->createView(),
                    'songs' => $songs
                ));
    }


    // Display the form
    return $app['twig']->render('search.html', array(
                'form' => $form->createView()
            ));
})
->method('GET|POST')
->bind('search')
;
           


//This Part of the contoller is used to insert an item in the PlayList
$app->get('/insert/{id}', function ($id) use ($app) {

    $item = new \RH\Model\PlayItem();


    //TODO : Time is only a temporary solution. This will have to change.
    $item->setOrder(time());

    $song = RH\Model\SongQuery::create()->findPk($id);

    $item->setSong($song);
    $item->save();

    //return $app->redirect($app['url_generator']->generate('search'));
    return $app->redirect($app->path('homepage'));
})
->bind('insert')
;



$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
