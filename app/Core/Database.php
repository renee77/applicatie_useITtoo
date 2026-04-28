<?php

// ============================================================
// Database.php — databaseverbinding via het Singleton-patroon
//
// Het Singleton-patroon zorgt ervoor dat er gedurende de hele
// request maar één PDO-verbinding wordt aangemaakt. Elke DAO
// die Database::getConnection() aanroept krijgt dezelfde
// instantie terug, zonder dat er opnieuw verbinding gemaakt
// wordt. Dit is efficiënter en voorkomt meerdere open
// verbindingen naar de database.
// ============================================================

// Laadt de autoloader van Composer.
// Composer heeft vlucas/phpdotenv geïnstalleerd in de vendor/ map.
// De autoloader zorgt ervoor dat alle Composer-packages automatisch
// beschikbaar zijn zonder dat je ze handmatig hoeft te importeren.
require_once __DIR__ . '/../../vendor/autoload.php';

// Maak een Dotenv-instantie aan die het .env.local bestand inleest.
//
// createImmutable():
//   Laadt de variabelen als onveranderbaar — een al bestaande
//   omgevingsvariabele wordt niet overschreven. Dit is de
//   aanbevolen aanpak voor productieomgevingen.
//
// Eerste argument: het pad naar de map waar .env.local staat.
//   __DIR__ is de map van dit bestand (app/Core/).
//   ../../ gaat twee mappen omhoog naar de projectroot.
//
// Tweede argument: de bestandsnaam.
//   Standaard zoekt Dotenv naar '.env'. Door '.env.local' mee
//   te geven lezen we expliciet het lokale configuratiebestand.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../', '.env.local');

// Lees het .env.local bestand in en zet alle variabelen
// beschikbaar via $_ENV en getenv().
// Gooit een exception als het bestand niet gevonden wordt.
$dotenv->load();


class Database
{

    // Bewaart de enige PDO-instantie voor de hele request.
    // static: de waarde blijft beschikbaar tussen aanroepen.
    // ?PDO:  begint als null, wordt gevuld bij de eerste aanroep.
    private static ?PDO $instance = null;

    // De constructor is private zodat niemand buiten deze klasse
    // een nieuw Database-object kan aanmaken met 'new Database()'.
    // Toegang loopt uitsluitend via getConnection() hieronder.
    private function __construct() {}

    // Geeft de PDO-verbinding terug.
    // static: aanroepbaar zonder object — Database::getConnection()
    // PDO:    het returntype, zodat de IDE en PHP weten wat terugkomt.
    public static function getConnection(): PDO
    {

        // Controleer of er al een verbinding bestaat.
        // Zo ja: sla de rest over en geef de bestaande verbinding terug.
        // Zo nee: maak een nieuwe verbinding aan (zie hieronder).
        if (self::$instance === null) {

            // Bouw de DSN (Data Source Name): de verbindingsstring voor PDO.
            // De waarden komen uit $_ENV, gevuld door Dotenv vanuit .env.local.
            //
            // mysql:    het databasetype
            // host=     het adres van de databaseserver (localhost bij LAMPP)
            // dbname=   de naam van de database
            // charset=  tekenset — utf8mb4 ondersteunt ook emoji's en speciale tekens
            $dsn = 'mysql:host=' . $_ENV['DB_HOST'] .
                ';dbname=' . $_ENV['DB_NAME'] .
                ';charset=utf8mb4';

            // Maak de PDO-verbinding aan met de DSN, gebruikersnaam en wachtwoord.
            // Het vierde argument is een array met opties:
            //
            //   ERRMODE_EXCEPTION:
            //     Bij een databasefout gooit PDO een PDOException.
            //     Zonder deze optie mislukken queries stil, wat moeilijk
            //     te debuggen is.
            //
            //   FETCH_ASSOC:
            //     Resultaten worden teruggegeven als associatieve arrays:
            //     ['product_id' => 1, 'naam' => 'Wortel']
            //     in plaats van genummerde arrays: [0 => 1, 1 => 'Wortel']
            self::$instance = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        // Geef de verbinding terug — of die nu net aangemaakt is
        // of al bestond vanuit een eerdere aanroep.
        return self::$instance;
    }
}
