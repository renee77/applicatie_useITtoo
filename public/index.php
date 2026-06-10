<?php

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../app/Routes/routes.php';

$session = new \App\Core\SessionManager();
$session->start();

// Bepaalt een actieve taal. Bij geen selectie is het automatisch NL
$lang = $session->getLanguage();

// Laad op basis hiervan het juiste taalbestand in.
$GLOBALS['text'] = require __DIR__ . '/../app/lang/' . $lang . '.php';

$router = new \App\Core\Router($_ENV['APP_BASE_PATH'] ?? '', $session);

\App\Routes\Routes::register($router, $session);

$router->run();
