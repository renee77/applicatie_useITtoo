<?php

require_once __DIR__ . '/../bootstrap.php';

$title = 'Welkom';
ob_start(); // start het opvangen van output, zodat we die later kunnen gebruiken in main.php
include __DIR__ . '/../app/Views/start/home.view.php';  // 2. Draai de view (echo's worden opgevangen)
$content = ob_get_clean(); // 3. Stop buffering, stop alles in $content
// ob_start() en ob_get_clean() vangen de output van de view op en stoppen die in $content, zodat main.php het op de juiste plek kan plaatsen.

include __DIR__ . '/../app/Views/layouts/main.php'; // 4. Laad de template — $content is nu gevuld
// main.php is het hoofdtemplate dat de header, footer en de content van de pagina samenvoegt. Het gebruikt $content om de specifieke inhoud van deze pagina in te voegen.
