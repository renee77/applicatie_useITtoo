<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>useITtoo</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/style/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/style/header.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/style/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/style/webshop.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/style/cart.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/style/recipes.css">
</head>

<body>
    <header>
        <div class="header container">
            <nav>
                <a href="<?= BASE_URL ?>/webshop.page.php" class="logo-link"><img src="<?= BASE_URL ?>/images/licht-logo.png" class="logo"
                        alt="logo van useITtoo"></a>
            </nav>

            <div id="search">
                <input type="text" placeholder="Search....">
            </div>
            <div id="login">
                <img src="<?= BASE_URL ?>/images/login-logo.png" id="loginLogo"
                    alt="login logo">
                <button class="light-button login" aria-label="Log in bij je account">Login</button>
                <img src="<?= BASE_URL ?>/images/winkelwagen-lichtgroen.png" id="winkelwagenLogo"
                    alt="winkelwagen logo">
            </div>
        </div>

    </header>
    <main>