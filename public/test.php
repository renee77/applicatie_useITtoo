<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/Core/Database.php';

try {
    $pdo = Database::getConnection();
    echo 'Verbinding geslaagd!';
} catch (Exception $e) {
    echo 'Fout: ' . $e->getMessage();
}
