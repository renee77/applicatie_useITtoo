<div class="popup-overlay" id="loginOverlay">
    <div class="login-popup">

        <div class="login-popup__header">
            <h2>
                <?= __('login.login') ?>
            </h2>
            <button class="close dark-button" id="loginClose" aria-label="Sluiten">&#x2715;</button>
        </div>

        <?php if (!empty($error)) : ?>
            <p class="login-popup__error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/webshop/login" class="login-popup__form">
            <!-- Dit pakt de huidige URL op het moment dat de pagina geladen wordt en 
             plakt die mee in het formulier. De strtok knipt de querystring eraf. -->
            <input type="hidden" name="redirect_to" 
                value="<?= htmlspecialchars(strtok($_SERVER['REQUEST_URI'], '?')) ?>">
            <div class="login-popup__group">
                <label for="gebruikersnaam">
                    <?= __('login.username') ?>
                </label>
                <input type="text" id="gebruikersnaam" name="gebruikersnaam"
                       placeholder="<?= __('login.username') ?>"
                       value="<?= htmlspecialchars($_POST['gebruikersnaam'] ?? '') ?>"
                       required />
            </div>

            <div class="login-popup__group">
                <label for="wachtwoord">
                    <?= __('login.password') ?>
                </label>
                <input type="password" id="wachtwoord" name="wachtwoord"
                       placeholder="••••••••"
                       value="<?= htmlspecialchars($_POST['wachtwoord'] ?? '') ?>"
                       required />
            </div>

            <div class="login-popup__actions">
                <button type="submit" class="dark-button">
                    <?= __('login.login') ?>
                </button>
            </div>
        </form>

    </div>
</div>