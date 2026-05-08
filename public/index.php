<?php

require_once __DIR__ . '/../bootstrap.php';

$title = 'Welkom';
ob_start();
include __DIR__ . '/../app/Views/start/home.view.php';
$content = ob_get_clean();
// ob_start() en ob_get_clean() vangen de output van de view op en stoppen die in $content, zodat main.php het op de juiste plek kan plaatsen.

include __DIR__ . '/../app/Views/layouts/main.php';
