<?php

namespace App\Controllers;

use App\Core\SessionManager;
use App\DAO\ProductDAO;
use PDO;

class UploadController
{
    public function __construct(
        private SessionManager $session,
        private ?ProductDAO $dao = null,
        private ?PDO $db = null
    ) {
    }

    private function mistakeRedirect(string $bericht, string $pad): void
    {
    // Sla de foutmelding op in de sessie zodat de view hem kan tonen
        $this->session->setFout($bericht);
    // Stuur de gebruiker terug naar de opgegeven pagina
        header('Location: ' . BASE_URL . $pad);
        exit;
    }

    public function handleCSVUpload(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Controleer of het bestand correct is geüpload.
            // 'error' is UPLOAD_ERR_OK (0) als alles goed is gegaan.
            // Anders is er iets misgegaan, bijv. bestand te groot of geen bestand.
            if ($_FILES['csv_bestand']['error'] !== UPLOAD_ERR_OK) {
                $this->mistakeRedirect("Fout bij uploaden van bestand", '/beheer/upload/csv');
            }

            // Open het tijdelijke bestand dat PHP heeft aangemaakt via fopen (file open).
            // r betekent read-only. Bestand wordt alleen ingelezen.
            $bestand = fopen($_FILES['csv_bestand']['tmp_name'], 'r');

            // Eerste rij wordt overgeslagen, zijn kolomnamen en geen product.
            fgetcsv($bestand);

            // Houdt in de gaten hoeveel producten succesvol zijn aangemaakt.
            $aangemaakt = 0;
            // Houdt in de gaten hoeveel fouten er zijn geweest.
            $fouten = [];
            // Signaleert bij welke rij we beginnen
            $rijnummer = 2;

            // Transactie starten vóór de loop
            $this->db->beginTransaction();

            while (($rij = fgetcsv($bestand, 1000, ',')) !== false) {
               // Nu door alle CSV-rijen gaan lopen.
               // fgetcsv() leest één rij tegelijk en geeft een array terug.
               // Geef aan dat het max 1000 tekens is, en dat het scheidngsteken ',' is.
               // Als einde van het bestand is bereikt, krijgen we false.
                try {
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


                 // Alles gelukt, opslaan
                    $this->db->commit();
                } catch (\InvalidArgumentException $e) {
               // Ongeldige productdata — negatieve prijs, naam te kort etc.
               // Komt uit de validatie in het Product model
                    $this->db->rollBack();
                    $fouten[] = "Rij $rijnummer: ongeldige data — " . $e->getMessage();
                } catch (\ValueError $e) {
             // Ongeldige eenheid — waarde bestaat niet in de Eenheid enum
             // Komt van Eenheid::from() als de waarde niet bekend is
                    $this->db->rollBack();
                    $fouten[] = "Rij $rijnummer: ongeldige eenheid '{$rij[3]}'";
                } catch (\Exception $e) {
         // Onverwachte fout — vang alles op wat hierboven niet is gevangen
                    $this->db->rollBack();
                    $fouten[] = "Rij $rijnummer: onverwachte fout — " . $e->getMessage();
                }

                $rijnummer++;
            }

            fclose($bestand);

     // Stel de melding samen op basis van het resultaat
            if (empty($fouten)) {
                    // Alles gelukt
                    $this->session->setMelding($aangemaakt + __('notifs.products_imported'));
            } else {
                   // Deels gelukt — toon hoeveel gelukt zijn en welke rijen mislukten
                   $foutMelding = "$aangemaakt product(en) geïmporteerd. "
                . count($fouten) . " rij(en) overgeslagen:\n"
                . implode("\n", $fouten);
                   $this->session->setFout($foutMelding);
            }

            header('Location: ' . BASE_URL . '/beheer/upload/csv');
            exit;
        }
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
            $this->mistakeRedirect("Fout bij uploaden van bestand", '/beheer/upload/afbeelding');
        }

      // Hier wordt nog een extra check uitgevoerd of het geuploade bestand wel echt png/jpg/jpeg is.
      // Anders kan het worden aangepast in de html en komen hackers er dus doorheen.
      // Hiervoor geef ik dus eerst aan welke soorten er zijn toegestaan.
        $afbTypes = ['image/png', 'image/jpeg'];
      // En daarna laat ik het type binnenhalen.
      // tmp_name geeft aan wat het tijdelijke pad op de server is.
        $mimeType = mime_content_type($bestand['tmp_name']);

      // Daarna check ik of het overeenkomt.
      // Als het type uit de mimeType dus niet overeenkomt met de opties in mijn array,
      //gaat er ook een foutmelding terug.
        if (!in_array($mimeType, $afbTypes)) {
            $this->mistakeRedirect("Alleen PNG en JPG bestanden zijn toegestaan.", '/beheer/upload/afbeelding');
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
            $this->mistakeRedirect("Fout bij opslaan van bestand", '/beheer/upload/afbeelding');
        }

        $this->session->setMelding(__('notifs.image_imported'));
        header('Location: ' . BASE_URL . '/beheer/upload/afbeelding');
        exit;
    }
}
