<div class="popup-overlay" id="loginOverlay">
    <div class="login-popup">

        <div class="login-popup__header">
            <h2>Inloggen</h2>
            <button class="close dark-button" id="loginClose" aria-label="Sluiten">&#x2715;</button>
        </div>

        <?php if (!empty($error)) : ?>
            <p class="login-popup__error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/klant/login" class="login-popup__form">
            <!-- Dit pakt de huidige URL op het moment dat de pagina geladen wordt en 
             plakt die mee in het formulier. De strtok knipt de querystring eraf. -->
            <input type="hidden" name="redirect_to" 
            value="<?= htmlspecialchars(BASE_URL . strtok($_SERVER['REQUEST_URI'], '?')) ?>">
            <div class="login-popup__group">
                <label for="gebruikersnaam">Gebruikersnaam</label>
                <input type="text" id="gebruikersnaam" name="gebruikersnaam"
                       placeholder="Gebruikersnaam"
                       value="<?= htmlspecialchars($_POST['gebruikersnaam'] ?? '') ?>"
                       required />
            </div>

            <div class="login-popup__group">
                <label for="wachtwoord">Wachtwoord</label>
                <input type="password" id="wachtwoord" name="wachtwoord"
                       placeholder="••••••••"
                       value="<?= htmlspecialchars($_POST['wachtwoord'] ?? '') ?>"
                       required />
            </div>

            <div class="login-popup__actions">
                <button type="submit" class="dark-button">Inloggen</button>
            </div>
        </form>

    </div>
</div>