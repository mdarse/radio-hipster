<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html', array());
})
->bind('homepage')
;

$app->get('/upload', function () use ($app) {
    //TODO
})
->bind('upload')
;


$app->match('/search', function (Request $request) use ($app) {
    $data = array(
        'name' => 'Your name',
        'email' => 'Your email',
    );

    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('name')
        ->add('email')
        ->add('gender', 'choice', array(
            'choices' => array(1 => 'male', 2 => 'female'),
            'expanded' => true,
        ))
        ->getForm();
    
    if ('POST' == $request->getMethod()) {
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();

            // do something with the data

            // redirect somewhere
            return $app->redirect('...');
        }
    }
    
    // display the form
    return $app['twig']->render('search.html', array('form' => $form->createView﻿﻿()));
})
->method('GET|POST')
->bind('search');


$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
