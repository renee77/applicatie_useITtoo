<?php

?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?> || UseITtoo</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/shared/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/beheer/beheer.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/beheer/beheer.homepage.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/beheer/beheer.product.overview.view.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/beheer/beheer.product.nieuw.view.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/beheer/beheer.upload.css">
</head>
<body>
  <header>
    <a href="<?= BASE_URL ?>/"><img src="<?= BASE_URL ?>/assets/images/logos/licht-logo.png"/></a>
    <ul>
      <li class="navList">
        <a href="<?= BASE_URL ?>/beheer" class="navLinks <?= $currentPage === 'home' ? 'selected' : '' ?>">
          Home</a>
      </li>
      <li class="navList">
        <a href="<?= BASE_URL ?>/beheer/product" class="navLinks <?= $currentPage === 'product' ? 'selected' : '' ?>">
          Producten</a>
      </li>
      <li class="navList">
        <a href="<?= BASE_URL ?>/beheer/zoekterm"  class="navLinks <?= $currentPage === 'zoekterm' ? 'selected' : '' ?>" >Zoektermen</a>
      </li>
      <li class="navList">
        <a href="<?= BASE_URL ?>/beheer/upload" class="navLinks <?= $currentPage === 'upload' ? 'selected' : '' ?>">
          Uploaden</a>
      </li>
      <li class="navList">
        <a href="#" class="navLinks <?= $currentPage === 'home' ? 'rapportage' : '' ?>">Rapportages</a>
      </li>
      <li class="navList">
        <a href="<?= BASE_URL ?>/logout" class="logOut navLinks">Log uit</a>
      </li>
    </ul>
  </header>
