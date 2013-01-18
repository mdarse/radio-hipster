<?php

use RH\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TranslationServiceProvider;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new TranslationServiceProvider(), array(
    'locale_fallback' => 'fr',
));
$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(__DIR__.'/../templates'),
    // 'twig.options' => array('cache' => __DIR__.'/../cache'),
));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
}));

$app->register(new Propel\Silex\PropelServiceProvider(), array(
    // 'propel.path'        => __DIR__.'/path/to/Propel.php',
    'propel.config_file' => __DIR__.'/../config/radio-hipster-conf.php',
    'propel.model_path'  => __DIR__.'/../src',
));

$app->register(new Silex\Provider\SessionServiceProvider());

return $app;
