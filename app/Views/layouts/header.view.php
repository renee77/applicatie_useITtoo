<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'useITtoo' ?></title>
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
<<<<<<< HEAD
                <a href="<?= BASE_URL ?>/" class="logo-link">
                    <img src="<?= BASE_URL ?>/assets/images/logos/licht-logo.png" 
                        class="logo" alt="logo van useITtoo"></a>
=======
                <a href="<?= BASE_URL . "/webshop" ?>/" class="logo-link"><img src="<?= BASE_URL ?>/assets/images/logos/licht-logo.png" class="logo"
                        alt="logo van useITtoo"></a>
>>>>>>> main
            </nav>

            <div id="search">
                <input type="text" placeholder="Search....">
            </div>
            <div id="login">
                <!-- Wrapper rond het login-icoon en dropdown, zodat de dropdown relatief 
                    hieraan gepositioneerd kan worden -->
                <div class="login-wrapper">
                    <!-- Login icoon, altijd zichtbaar -->
                    <img src="<?= BASE_URL ?>/assets/images/clickables/login-logo.png" id="loginLogo" alt="login logo">

                    <?php if ($session->isLoggedIn()) : ?>
                        <!-- Klant is ingelogd: toon de dropdown met naam en uitlogknop -->
                        <div class="login-dropdown">
                            <!-- Voornaam ophalen uit de sessie en veilig tonen met htmlspecialchars -->
                            <!-- htmlspecialchars voorkomt XSS: tekens zoals < > " 
                             worden omgezet naar HTML-entiteiten -->
                            <p>U bent ingelogd als <strong><?= htmlspecialchars($session->getVoornaam()) ?></strong></p>
                    
                            <!-- POST-formulier naar de logout route — verwijdert de sessie en redirect naar home -->
                            <form action="<?= BASE_URL ?>/logout" method="POST">
                                <button type="submit" class="light-button">Uitloggen</button>
                            </form>
                        </div>
                    <?php else : ?>
                        <!-- Klant is niet ingelogd: toon de gewone loginknop -->
                        <button class="light-button login" aria-label="Log in bij je account">Login</button>
                    <?php endif; ?>
                </div>
                    
                <!-- Winkelwagen icoon, staat buiten de login-wrapper zodat hij niet mee hoverd -->
                <img src="<?= BASE_URL ?>/assets/images/clickables/winkelwagen-lichtgroen.png" 
                    id="winkelwagenLogo" alt="winkelwagen logo">
            </div>
        </div>

    </header>
    
