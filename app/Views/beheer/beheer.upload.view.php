<?php

$currentPage = 'upload';
$pageTitle = 'Uploaden';
?>
<section class="choices">
  <a href="<?= BASE_URL ?>/beheer/upload/csv"><div class="choice-block">
    <?= __('upload.upload_csv') ?>
  </div></a>

  <a href="<?= BASE_URL ?>/beheer/upload/afbeelding"><div class="choice-block">
    <?= __('upload.upload_image') ?>
  </div></a>
</section>
