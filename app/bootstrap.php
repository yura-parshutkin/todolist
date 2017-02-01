<?php

require __DIR__. '/../vendor/autoload.php';

use Silex\Application;

$app           = new Application();
$params        = require __DIR__ . '/config/parameters.php';
$app['debug']  = $params['debug'];

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver'   => $params['driver'],
        'dbname'   => $params['dbname'],
        'host'     => $params['host'],
        'user'     => $params['user'],
        'password' => $params['password'],
        'charset'  => $params['charset'],
    ],
]);
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__. '/../src/Frontend/Resources/views',
]);
$app->register(new Silex\Provider\SessionServiceProvider());

require __DIR__ . '/config/service.php';
require __DIR__ . '/config/security.php';


$app->view(function($controllerResult) use ($app) {
    return $app->json($controllerResult);
});

return $app;