<?php

$actieveTaal = $session->getLanguage(); ?>
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
    <a href="<?= BASE_URL ?>/"><img src="<?= BASE_URL ?>/assets/images/logos/licht-logo.png" alt="<?= __('header.alt_logo') ?>"/></a>
    <div class="lang-switcher">
      <a href="<?= BASE_URL ?>/lang/set?lang=nl"
      class="lang-btn <?= $actieveTaal === 'nl' ? 'active' : '' ?>">NL</a>
      <a href="<?= BASE_URL ?>/lang/set?lang=en"
      class="lang-btn <?= $actieveTaal === 'en' ? 'active' : '' ?>">EN</a>
    </div>
    <nav>
      <ul>
        <li class="navList">
          <a href="<?= BASE_URL ?>/beheer" class="navLinks <?= $currentPage === 'home' ? 'selected' : '' ?>">
            <?= __('admin_header.home') ?></a>
        </li>
        <li class="navList">
          <a href="<?= BASE_URL ?>/beheer/product" class="navLinks <?= $currentPage === 'product' ? 'selected' : '' ?>">
            <?= __('admin_header.products') ?></a>
        </li>
        <li class="navList">
          <a href="<?= BASE_URL ?>/beheer/zoekterm" 
          class="navLinks <?= $currentPage == 'zoekterm' ? 'selected' : '' ?>" >
          <?= __('admin_header.search_terms') ?></a>
        </li>
        <li class="navList">
          <a href="<?= BASE_URL ?>/beheer/upload" class="navLinks <?= $currentPage === 'upload' ? 'selected' : '' ?>">
            <?= __('admin_header.upload') ?>
        </a>
        </li>
        <li class="navList">
          <a href="#" class="navLinks <?= $currentPage === 'home' ? 'rapportage' : '' ?>">
            <?= __('admin_header.reports') ?></a>
        </li>
        <li class="navList">
          <a href="<?= BASE_URL ?>/logout" class="logOut navLinks"><?= __('admin_header.logout') ?></a>
        </li>
      </ul>
    </nav>
  </header>
