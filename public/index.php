<?php
// Front Controller - alle requests lopen hier doorheen
require_once '../vendor/autoload.php';

use App\Core\Router;

$router = new Router();
$router->dispatch();
