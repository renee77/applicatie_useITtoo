<?php

$currentPage = 'home';
$pageTitle = 'Homepage';
?>

<div class="main-container">
  <img src="<?= BASE_URL ?>/assets/images/logos/licht-logo.png" class="homepageImg"/>
  <h1>Welkom<?= htmlspecialchars(" " . $voornaam) ?>!</h1>

  <div class="quickButtons">
    <button href="#" class="light-button">Snel een nieuw product maken</button>
    <button href="#" class="light-button">Snel een CSV Bestand uploaden</button>
    <button href="#" class="light-button">Snel naar klantbestellingen</button>
  </div>
</div>
