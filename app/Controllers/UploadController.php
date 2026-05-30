<?php

namespace App\Controllers;

use App\Core\SessionManager;
use App\DAO\ProductDAO;
use App\Models\Eenheid;
use App\Models\Product;
use PDO;

class UploadController
{
  public function __construct(
    private SessionManager $session,
    private ?ProductDAO $dao = null,
    private ?PDO $db = null
  ) {}

  public function handleCSVUpload(): void
  {
            // Controleer of het bestand correct is geüpload.
            // 'error' is UPLOAD_ERR_OK (0) als alles goed is gegaan.
            // Anders is er iets misgegaan, bijv. bestand te groot of geen bestand.
            if ($_FILES['csv_bestand']['error'] !== UPLOAD_ERR_OK) {
                $this->session->setFout("Fout bij uploaden van bestand.");
                header('Location: ' . BASE_URL . '/beheer/upload/csv');
                exit;
            }

            // Open het tijdelijke bestand dat PHP heeft aangemaakt via fopen (file open).
            // r betekent read-only. Bestand wordt alleen ingelezen.
            $bestand = fopen($_FILES['csv_bestand']['tmp_name'], 'r');

            // Eerste rij wordt overgeslagen, zijn kolomnamen en geen product.
            fgetcsv($bestand);

            // Houdt in de gaten hoeveel producten succesvol zijn aangemaakt.
            $aangemaakt = 0;
            // Houdt in de gaten hoeveel fouten er zijn geweest.
            $fouten = 0;

            // Transactie starten vóór de loop
        $this->db->beginTransaction();

        try {
          // Nu door alle CSV-rijen gaan lopen.
          // fgetcsv() leest één rij tegelijk en geeft een array terug.
          // Geef aan dat het max 1000 tekens is, en dat het scheidngsteken ',' is.
          // Als einde van het bestand is bereikt, krijgen we false.
            while (($rij = fgetcsv($bestand, 1000, ',')) !== false) {
                // Voor elke rij wordt een Product gemaakt.
                    // Het is belangrijk dat de volgorde van CSV bestand klopt met de nummering.
                    // Anders wordt informatie bij verkeerde kolom geplaatst.
                    $product = new \App\Models\Product(
                        // naam
                        trim($rij[0]),
                        // prijs
                        (float) $rij[1],
                        // verkoop_gewicht
                        (float) $rij[2],
                        // eenheid (moet kloppen met ENUM)
                        \App\Models\Eenheid::from(trim($rij[3])),
                        // Omschrijving, kan null zijn.
                        trim($rij[4]) ?: null,
                        // Leverancier, kan null zijn.
                        trim($rij[5]) ?: null,
                        // foto_url, kan null zijn.
                        trim($rij[6]) ?: null,
                    );
                    // Vervolgens wordt het product aangemaakt, en wordt er dus opgeplust bij aangemaakt variabele.
                    $this->dao->addProduct($product);
                    $aangemaakt++;
            }

                // Alles gelukt, opslaan
                $this->db->commit();

            } catch (\Exception $e) {
                // Als er een fout in de rij zit, wordt de alles teruggedraaid.
                // Fout kan zijn, negatieve prijs, ongeldige eenheid, prijs als string oid..
                $this->db->rollBack();
                $fouten++;
            }

            fclose($bestand);
            $this->session->setMelding("$aangemaakt product(en) geïmporteerd, $fouten overgeslagen.");
            header('Location: ' . BASE_URL . '/beheer/product');
            exit;
        }

  public function sendCSVTemplate(): void
  {
    // Geef aan dat het een CSV bestand gaat zijn.
    header('Content-Type: text/csv');
    // Geef aan dat het een download gaat worden en geen nieuwe pagina, met de naam
    // product_template.csv
    header('Content-Disposition: attachment; filename="product_template.csv"');

    // Open een output buffer zodat we fputcsv() kunnen gebruiken
    $output = fopen('php://output', 'w');

    // Eerst zetten we de header in de csv, geven hiermee de kolommen aan.
    // Deze matcht met wat de import verwacht.
    fputcsv($output, [
        'naam',
        'prijs',
        'verkoop_gewicht',
        'eenheid',
        'omschrijving',
        'leverancier',
        'foto_url'
    ]);

    // Een voorbeeldrij, waarop te zien is hoe de data moet worden geschreven.
    fputcsv($output, [
        'Wortel',
        '1.95',
        '1000',
        'kg',
        'Verse wortels van de boer',
        'Boer Koen',
        'wortel.jpg'
    ]);

    // Sluit het document en zorg ervoor dat het kan worden gedownload.
    fclose($output);
    exit;
  }

  public function uploadImage(): void
  {
    // Haal het bestand binnen onder de identifier foto_url
    $bestand = $_FILES['foto_url'];

    // Dubbelcheck dat er niets fout is gegaan met de upload.
    // De $_FILES superglobal haalt ook errors binnen, dus deze registreert hij.
    // 0 Betekent geen fout.
    if ($bestand['error'] !== UPLOAD_ERR_OK) {
        // Als er een fout melding is, geef dit aan.
        $this->session->setFout("Fout bij uploaden van bestand");
        // En redirect met header naar de pagina.
        header('Location: ' . BASE_URL . '/beheer/upload/afbeelding');
        exit;
    }

    // Hier wordt nog een extra check uitgevoerd of het geuploade bestand wel echt png/jpg/jpeg is.
    // Anders kan het worden aangepast in de html en komen hackers er dus doorheen.
    // Hiervoor geef ik dus eerst aan welke soorten er zijn toegestaan.
    $afbTypes = ['image/png', 'image/jpeg'];
    // En daarna laat ik het type binnenhalen.
    // tmp_name geeft aan wat het tijdelijke pad op de server is.
    $mimeType = mime_content_type($bestand['tmp_name']);

    // Daarna check ik of het overeenkomt.
    // Als het type uit de mimeType dus niet overeenkomt met de opties in mijn array, gaat er ook een foutmelding terug.
    if (!in_array($mimeType, $afbTypes)) {
        $this->session->setFout("Alleen PNG en JPG bestanden zijn toegestaan.");
        header('Location: ' . BASE_URL . '/beheer/upload/afbeelding');
        exit;
    }

    $extensie = '';
    // Nu checken we welk type het is. PNG of JPG.
    if ($mimeType === 'image/png') {
        $extensie = 'png';
    } else {
        $extensie = 'jpg';
    }

    // Nu moet ik alleen de naam ophalen, zonder de extensie erachter.
    // Hiervoor gebruik ik pathinfo, die het pad opsnijdt in verschillende stukken.
    $bestandsnaam =  pathinfo($bestand['name'], PATHINFO_FILENAME);

    // Bepaal de doelmap
    $uploadMap = __DIR__ . '/../public/assets/images/products/';
    // Daarna beschrijf ik het volledige doelpad, op basis van alles wat nu is opgevraagd.
    $doelpad = $uploadMap . $bestandsnaam . '.' . $extensie;

    // Bij het uploaden van een bestand, slaat PHP het eerst tijdelijk op.
    // Hier komt die TMP_Name ook vandaan.
    // De move_uploaded file verplaatst het vervolgens naar een definitieve locatie.
    // Het checkt ook op het via een upload is binnengekomen
    if (!move_uploaded_file($bestand['tmp_name'], $doelpad)) {
        $this->session->setFout("Fout bij opslaan van bestand.");
        header('Location: ' . BASE_URL . '/beheer/upload/afbeelding');
        exit;
    }

    $this->session->setMelding("Afbeelding succesvol geüpload!");
    header('Location: ' . BASE_URL . '/beheer/upload/afbeelding');
    exit;
  }
}