<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/', function () use ($app) {
    
    $songs = \RH\Model\PlayItemQuery::create()
            ->find();

    
    return $app['twig']->render('index.html', array(
                    'songs' => $songs
                ));
})
->bind('homepage')
;

$app->get('/upload', function () use ($app) {
    //TODO
})
->bind('upload')
;


//This part of the controller is used to controle the search module
$app->match('/search', function (Request $request) use ($app) {
    $form = $app['form.factory']->createBuilder('form')
            ->add('search')
            ->getForm();



    //Il there are something in the POST
    if ('POST' == $request->getMethod()) {
        $form->bind($request);
        $data = $form->getData();


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
    return $app->redirect($app->path('search'));
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
