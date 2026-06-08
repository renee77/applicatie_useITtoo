<?php

$currentPage = 'home';
$pageTitle = 'Homepage';
?>

<div class="main-container">
  <img src="<?= BASE_URL ?>/assets/images/logos/licht-logo.png" class="homepageImg"/>
  <h1>Welkom<?= htmlspecialchars(" " . $voornaam) ?>!</h1>

  <div class="quickButtons">
     <a class="orangeBtn" 
          href="<?= BASE_URL ?>/beheer/product/nieuw">Snel een nieuw product maken
    </a>
    <a class="orangeBtn" 
          href="<?= BASE_URL ?>/beheer/upload/csv">Snel een CSV Bestand uploaden
    </a>
    <a class="orangeBtn" 
          href="<?= BASE_URL ?>/beheer/zoekterm">Snel naar zoektermen
    </a>
  </div>
</div>
