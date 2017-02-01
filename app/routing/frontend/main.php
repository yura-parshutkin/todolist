<?php

$frontend = $app['controllers_factory'];
$frontend->get('/', 'controller.homepage:indexAction');
$frontend->get('/login', 'controller.auth:loginAction');
$frontend->get('/registration', 'controller.auth:registrationAction');
$frontend->post('/registration', 'controller.auth:registrationProcessAction');

return $frontend;