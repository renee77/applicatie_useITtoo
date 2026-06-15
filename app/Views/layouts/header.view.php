<?php $actieveTaal = $session->getLanguage(); ?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "useITtoo" ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/shared/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/shared/header.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/shared/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/shared/home.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/webshop/webshop.css">
</head>

<body>
    <header>
        <div class="header container">
            <nav>
                <a href="<?= BASE_URL ?>/" class="logo-link">
                    <img src="<?= BASE_URL ?>/assets/images/logos/licht-logo.png" 
                        class="logo" alt="<?= __('header.alt_logo') ?>">
                    </a>
                
                <div class="lang-switcher">
                    <a href="<?= BASE_URL ?>/lang/set?lang=nl"
                    class="lang-btn <?= $actieveTaal === 'nl' ? 'active' : '' ?>">NL</a>
                    <a href="<?= BASE_URL ?>/lang/set?lang=en"
                    class="lang-btn <?= $actieveTaal === 'en' ? 'active' : '' ?>">EN</a>
                </div>
            </nav>

            <form action="<?= BASE_URL ?>/zoeken" method="GET" id="search">
                <input type="text" name="zoekterm" placeholder="<?= __('header.search_value') ?>" aria-label="<?= __('header.search_value') ?>">
                <button type="submit" class="light-button"><?= __('header.search') ?></button>
            </form>
            <div id="header-right">
                <div id="login">
                    <!-- Wrapper rond het login-icoon en dropdown, zodat de dropdown relatief
                        hieraan gepositioneerd kan worden -->
                    <div class="login-wrapper">
                        <!-- Login icoon, altijd zichtbaar -->
                        <button type="button" id="loginLogo" class="icon-btn" aria-label="<?= __('header.aria_login') ?>">
                            <img src="<?= BASE_URL ?>/assets/images/clickables/login-logo.png" alt="">
                        </button>

                        <?php if ($session->isLoggedIn()) : ?>
                            <!-- Klant is ingelogd: toon de dropdown met naam en uitlogknop -->
                            <div class="login-dropdown">
                                <!-- Voornaam ophalen uit de sessie en veilig tonen met htmlspecialchars -->
                                <!-- htmlspecialchars voorkomt XSS: tekens zoals < > "
                                 worden omgezet naar HTML-entiteiten -->
                                <p><?= __('header.logged_in') ?>
                                        <strong><?= htmlspecialchars($session->getVoornaam()) ?></strong></p>

                                <!-- POST-formulier naar de logout route — 
                                 verwijdert de sessie en redirect naar home -->
                                <form action="<?= BASE_URL ?>/logout" method="POST">
                                    <button type="submit" class="light-button">
                                        <?= __('header.logout') ?>
                                    </button>
                                </form>
                            </div>
                        <?php else : ?>
                            <!-- Klant is niet ingelogd: toon de gewone loginknop -->
                            <button class="light-button login" aria-label="<?= __('header.aria_login') ?>">
                                Login
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Winkelwagen icoon, staat buiten de login-wrapper zodat hij niet mee hoverd -->
                    <button type="button" id="winkelwagenLogo" class="icon-btn" aria-label="<?= __('alt_shopping_cart') ?>">
                        <img src="<?= BASE_URL ?>/assets/images/clickables/winkelwagen-lichtgroen.png" alt="">
                    </button>
                </div>

                <!-- Hamburger knop: alleen zichtbaar op mobiel, vervangt de login/winkelwagen iconen -->
                <button id="hamburger" type="button" aria-label="Menu openen" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>

    </header>
    
