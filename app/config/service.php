<?php

$app['repository.task'] = function($app) {
    return new App\Repository\TaskDbRepository($app['db']);
};

$app['repository.todo_list'] = function($app) {
    return new App\Repository\TodoListDbRepository($app['db']);
};

$app['repository.user'] = function($app) {
    return new App\Repository\UserDbRepository($app['db']);
};

$app['job.registration'] = function($app) {
    return new App\Job\RegistrationJob(
        $app['repository.user'],
        $app['security.default_encoder'],
        $app['validator'],
        $app['repository.todo_list']
    );
};

$app['controller.task'] = function($app){
    return new Api\Controller\TaskController(
        $app['repository.task'],
        $app['repository.todo_list'],
        $app['validator'],
        null
    );
};

$app['controller.auth'] = function($app) {
    return new Frontend\Controller\SecurityController(
        $app['twig'],
        $app['security.last_error'],
        $app['job.registration']
    );
};

$app['controller.homepage'] = function($app) {
    return new Frontend\Controller\HomepageController(
        $app['twig'],
        $app['repository.todo_list'],
        $app['user']
    );
};