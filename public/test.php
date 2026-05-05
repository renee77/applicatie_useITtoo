<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../bootstrap.php'; // ← laadt autoloader + Dotenv

use App\Core\Database; // ← namespace correct met hoofdletters

try {
    $pdo = app\Core\Database::getConnection();
    echo 'Verbinding geslaagd!';
} catch (Exception $e) {
    echo 'Fout: ' . $e->getMessage();
}
