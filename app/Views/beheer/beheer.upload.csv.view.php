<?php

$currentPage = 'upload';
$pageTitle = __('upload_csv.header_title');
?>
<h1><?= __('upload_csv.title') ?></h1>
<h3><?= __('upload_csv.important') ?></h3>
<p><?= __('upload_csv.text') ?></p>

<pre>
  naam,prijs,verkoop_gewicht,eenheid,omschrijving,leverancier,foto_url
  Wortel,1.95,1,kg,Verse wortels, Boer Koen uit Oudenbosch,wortel
  Appel,1.49,2,kg,Verse appels, Boer Anna uit Breda,appel
</pre>

<p><?= __('upload_csv.warning_conditions') ?></p>
<p><?= __('upload_csv.units') ?></p>
<ul>
  <li class="list_eenheden">kg</li>
  <li class="list_eenheden">gr</li>
  <li class="list_eenheden">stuks</li>
  <li class="list_eenheden">ml</li>
</ul>

<!--enctype multipart/form-data is nodig om bestanden via post te versturen. 
Anders kunnen de bestanden NIET worden verzonden. -->
<form method="POST" 
action="<?= BASE_URL ?>/beheer/upload/csv"
enctype="multipart/form-data"
class="csv-form">
  <input type="file" name="csv_bestand" accept=".csv" class="csv-input" required>
  <button type="submit" class="orangeBtn uploadBtn"><?= __('upload_csv.upload') ?></button>
</form>

<section class="downloadTemplate">
  <p>
    <?= __('upload_csv.download_text') ?>
  </p>
  <a href="<?= BASE_URL ?>/beheer/upload/csv/template" id="templateDownloader">
      <?= __('upload_csv.download_link') ?>
  </a>
</section>