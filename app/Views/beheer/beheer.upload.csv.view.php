<?php

$currentPage = 'upload';
$pageTitle = 'Uploaden';
?>
<h1>Upload hier je CSV-bestand</h1>
<h3>Belangrijke informatie!</h3>
<p>Om het CSV-bestand goed te kunnen verwerken, moet de tekst in het bestand als volgt worden ingestuurd:</p>

<pre>
  naam,prijs,verkoop_gewicht,eenheid,omschrijving,leverancier,foto_url
  Wortel,1.95,1,kg,Verse wortels, Boer Koen uit Oudenbosch,wortel
  Appel,1.49,2,kg,Verse appels, Boer Anna uit Breda,appel
</pre>

<p>Als dit niet op deze manier wordt geupload, <strong>wordt de rij niet verwerkt!</strong></p>
<p>De volgende eenheden kunnen worden gebruikt:</p>
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
  <button type="submit" class="orangeBtn uploadBtn">Uploaden</button>
</form>

<section class="downloadTemplate">
  <p>Mocht je willen, kun je hieronder een CSV-voorbeeld downloaden, met de juiste kolommen!</p>
  <a href="<?= BASE_URL ?>/beheer/upload/csv/template" id="templateDownloader">
      Download CSV template
  </a>
</section>