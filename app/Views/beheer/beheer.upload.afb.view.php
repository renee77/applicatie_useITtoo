<?php

$currentPage = 'upload';
$pageTitle = 'Uploaden';
?>
<h1>Afbeelding uploaden</h1>
<p>Upload hier een afbeelding voor je producten. Het is belangrijk om de volgende punten te onthouden:</p>
<ul>
  <li class="list_eenheden">
  De naam die je afbeelding heeft als je hem upload, is de naam waaronder hij wordt geregistreerd. 
  Zorg er voor dat het bestand een duidelijke naam heeft.</li>
  <li class="list_eenheden">De afbeeldingen kunnen enkel de typen .png, .jpg, .jpeg zijn.</li>
</ul>
<br />
<!--In mijn form is de enctype, de encryption type. 
Hiermee geef ik aan dat het een bestand is wat er wordt geupload.-->
<form method="POST" action="<?= BASE_URL ?>/beheer/upload/afbeelding" 
enctype="multipart/form-data">
  <div class="form__group">
    <label class="form__groupLabel" for="foto_url">Afbeelding:</label>
    <!--Hier geven we aan welke waarden de input kan gaan accepteren.-->
    <input class="form__groupInput" type="file" id="foto_url" 
    name="foto_url" accept=".png,.jpg,.jpeg" required />
  </div>
  <input type="submit" value="Uploaden" class="orangeBtn btn" />
</form>
