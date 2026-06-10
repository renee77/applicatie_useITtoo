<?php

$currentPage = 'upload';
$pageTitle = __('upload_csv.header_title');
?>
<h1><?= __('upload_img.title') ?></h1>
<p><?= __('upload_img.important') ?></p>
<ul>
  <li class="list_eenheden">
  <?= __('upload_img.img_name') ?></li>
  <li class="list_eenheden">
    <?= __('upload_img.img_types') ?>
  </li>
</ul>
<br />
<!--In mijn form is de enctype, de encryption type. 
Hiermee geef ik aan dat het een bestand is wat er wordt geupload.-->
<form method="POST" action="<?= BASE_URL ?>/beheer/upload/afbeelding" 
enctype="multipart/form-data">
  <div class="form__group">
    <label class="form__groupLabel" for="foto_url">
      <?= __('upload_img.img') ?>
    </label>
    <!--Hier geven we aan welke waarden de input kan gaan accepteren.-->
    <input class="form__groupInput" type="file" id="foto_url" 
    name="foto_url" accept=".png,.jpg,.jpeg" required />
  </div>
  <input type="submit" value="<?= __('upload_img.upload') ?>" class="orangeBtn btn" />
</form>
