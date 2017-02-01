<?php

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$api = $app['controllers_factory'];
$api->get('/todo-list/{list}/tasks', 'controller.task:getAllAction');
$api->post('/todo-list/{list}/tasks', 'controller.task:newAction');
$api->delete('/todo-list/{list}/tasks/{task}', 'controller.task:deleteAction');
$api->put('/todo-list/{list}/tasks/{task}', 'controller.task:putAction');
$api->patch('/todo-list/{list}/tasks/complete', 'controller.task:completeAction');
$api->patch('/todo-list/{list}/tasks/un-complete', 'controller.task:unCompleteAction');
$api->patch('/todo-list/{list}/tasks/remove-complete', 'controller.task:removeCompleteAction');

$api->before(function(Request $request, Application $app){

    /**
     * @var $list \App\Model\Entity\TodoList
     */
    $list = $app['repository.todo_list']->find(
        $request->attributes->get('list')
    );

    if (null === $list) {
        throw new NotFoundHttpException();
    }

    if (!$list->isOwner($app['user'])) {
        throw new AccessDeniedHttpException();
    }
});

$app->error(function(\App\Exception\ValidateException $e) use($app) {
    return $app->json([
        'errors'  => $e->getErrors()
    ]);
});

return $api;