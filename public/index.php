<?php

require_once __DIR__ . '/../bootstrap.php';

$session = new \App\Core\SessionManager();
$session->start();

$router = new \App\Core\Router($_ENV['APP_BASE_PATH'] ?? '', $session);

$registerRoutes = require __DIR__ . '/../app/Routes/routes.php';
$registerRoutes($router, $session);

$router->run();
