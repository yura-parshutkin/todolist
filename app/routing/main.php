<?php

$app->mount('/api', require __DIR__ . '/api/main.php');
$app->mount('', require __DIR__ . '/frontend/main.php');