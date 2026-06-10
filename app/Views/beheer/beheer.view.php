<?php

$currentPage = 'home';
$pageTitle = 'Homepage';
?>

<div class="main-container">
  <img src="<?= BASE_URL ?>/assets/images/logos/licht-logo.png" class="homepageImg"/>
  <h1><?= __('admin_homepage.welcome') ?> <?= htmlspecialchars(" " . $voornaam) ?>!</h1>

  <div class="quickButtons">
     <a class="orangeBtn" 
          href="<?= BASE_URL ?>/beheer/product/nieuw">
          <?= __('admin_homepage.quick_product') ?>
    </a>
    <a class="orangeBtn" 
          href="<?= BASE_URL ?>/beheer/upload/csv">
          <?= __('admin_homepage.quick_csv') ?>
    </a>
    <a class="orangeBtn" 
          href="<?= BASE_URL ?>/beheer/zoekterm">
          <?= __('admin_homepage.quick_search_terms') ?>
    </a>
  </div>
</div>
