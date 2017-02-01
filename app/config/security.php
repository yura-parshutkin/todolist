<?php

$app['security.firewalls'] = [
    'login' => [
        'pattern' => '^/(login|registration)$',
    ],
    'secured' => [
        'pattern' => '^.*$',
        'form'    => ['login_path' => '/login', 'check_path' => '/login-check'],
        'logout' => ['logout_path' => '/logout', 'invalidate_session' => true],
        'users'   => $app['repository.user'],
    ],
];