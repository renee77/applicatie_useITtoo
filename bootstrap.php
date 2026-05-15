<?php

// toont welke php errors er zijn
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ============================================================
// bootstrap.php — centraal opstartbestand van de applicatie
//
// Dit bestand wordt als allereerste geladen wanneer de
// applicatie start, via index.php of een ander instappunt.
//
// Het heeft twee verantwoordelijkheden:
//
// 1. Autoloader activeren
//    Composer genereert automatisch een autoloader in vendor/.
//    Die autoloader zorgt ervoor dat alle klassen en packages
//    beschikbaar zijn zonder dat je ze handmatig hoeft te
//    importeren met require of include. Door de autoloader
//    hier één keer te laden is hij beschikbaar voor de hele
//    applicatie.
//
// 2. Omgevingsvariabelen inladen
//    Gevoelige gegevens zoals databasewachtwoorden en API-sleutels
//    staan niet in de code maar in een .env.local bestand.
//    Dotenv leest dat bestand in en zet de variabelen beschikbaar
//    via $_ENV. Andere bestanden zoals Database.php kunnen die
//    variabelen daarna gewoon gebruiken zonder zelf te weten
//    waar ze vandaan komen.
//
// Waarom een apart bestand?
//    Zonder bootstrap.php zou elk bestand dat een database-
//    verbinding nodig heeft zelf de autoloader en Dotenv moeten
//    laden. Dat leidt tot dubbele code en maakt de applicatie
//    moeilijker te onderhouden. Door het opstarten op één plek
//    te centraliseren hoef je bij een wijziging — bijvoorbeeld
//    een ander .env bestand of een extra opstapstap — alleen
//    dit bestand aan te passen.
// ============================================================

// Activeer de Composer-autoloader.
// Alle klassen in vendor/ en app/ zijn hierna automatisch
// beschikbaar op basis van hun namespace en bestandspad.
require_once __DIR__ . '/vendor/autoload.php';

// Maak een Dotenv-instantie aan die het .env.local bestand inleest.
// __DIR__ verwijst hier naar de projectroot, waar .env.local staat.
// createImmutable() zorgt ervoor dat bestaande omgevingsvariabelen
// niet worden overschreven — de aanbevolen aanpak voor productie.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env.local');

// Lees het .env.local bestand in en zet alle variabelen
// beschikbaar via $_ENV en getenv().
// Gooit een exception als het bestand niet gevonden wordt.
$dotenv->load();

// Bepaal de basis-URL dynamisch op basis van de locatie van het entry point.
// dirname() haalt de map op van het huidige script (index.php in public/).
// rtrim() verwijdert een eventuele trailing slash voor consistente URL-opbouw.
// Gebruik BASE_URL als prefix voor alle links en assets in views, bijvoorbeeld:
// // Gebruik BASE_URL als prefix voor alle links en assets in views.

// dirname() met 2 als argument gaat twee niveaus omhoog:
// /eindopdracht_jaar1/public/index.php
//   → stap 1: /eindopdracht_jaar1/public
//   → stap 2: /eindopdracht_jaar1 dit is de base URL die we willen
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME'], 2), '/'));