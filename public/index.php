<?php

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../app/Routes/routes.php';

$session = new \App\Core\SessionManager();
$session->start();

$router = new \App\Core\Router($_ENV['APP_BASE_PATH'] ?? '', $session);

\App\Routes\Routes::register($router, $session);

$router->run();
