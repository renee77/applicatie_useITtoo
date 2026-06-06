<?php

namespace App\Controllers;

use App\Core\SessionManager;
use App\DAO\ZoektermDAO;
use App\DAO\ProductDAO;
use App\Models\Zoekterm;

class ZoekController
{
    public function __construct(
        private ZoektermDAO $zoektermDao,
        private ProductDAO $productDao,
        private SessionManager $session
    ) {
    }

    // ZoekController flow:

    public function zoeken(): array
    {
        // Ontvang $_GET['zoekterm'] + XSS sanitatie
        $zoekterm = trim(htmlspecialchars($_GET['zoekterm'] ?? '')) ;
        // Valideer — leeg of alleen spaties → flash melding "voer een geldige zoekterm in", niet opslaan, stop
        if ($zoekterm === '') {
            $this->session->setMelding("Voer een geldige zoekterm in");
            return [];
        }
        // Producten gevonden: Roep ProductDAO::zoekProducten(string $term) aan
        $gevondenProducten = $this->productDao->zoekProducten($zoekterm);

        if ($gevondenProducten) {
            // Geef producten  mee aan view
            return  ['producten' => $gevondenProducten];
        } else {
            // Geen producten gevonden:
            // Zet flash melding via SessionManager
            $this->session->setMelding("Geen producten gevonden");
            // Check via ZoektermDAO::bestaatZoekterm() of term al bestaat
            if ($this->zoektermDao->bestaatZoekterm($zoekterm)) {
                // Ja → ZoektermDAO::verhoogAantal()
                $this->zoektermDao->verhoogAantal($zoekterm);
                return [];
            } else {
                // Nee → ZoektermDAO::opslaanZoekterm() met aantal = 1
                $this->zoektermDao->opslaanZoekterm(new Zoekterm($zoekterm));
                return [];
            }
        }
    }
}
