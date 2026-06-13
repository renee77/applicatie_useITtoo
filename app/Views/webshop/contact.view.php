<div class="popup-overlay" id="contactOverlay">
    <div class="contact-popup">

        <div class="contact-popup__header">
            <h2><?= __('contact.contact_form') ?></h2>
            <button class="close dark-button" id="contactClose" aria-label="Sluiten">&#x2715;</button>
        </div>

        <?php if (!empty($contactFout)) : ?>
            <div class="contact-popup__error">
                <?php foreach (explode('|', $contactFout) as $fout) : ?>
                    <p><?= htmlspecialchars(trim($fout)) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/contact" class="contact-popup__form">
            <!-- Dit pakt de huidige URL op het moment dat de pagina geladen wordt en
             plakt die mee in het formulier. De strtok knipt de querystring eraf. -->
            <input type="hidden" name="redirect_to"
                value="<?= htmlspecialchars(strtok($_SERVER['REQUEST_URI'], '?')) ?>">
            <div class="contact-popup__group">
                <label for="voornaam">
                    <?= __('contact.first_name') ?>
                </label>
                <input type="text" id="voornaam" name="voornaam"
                       placeholder="<?= __('contact.first_name') ?>"
                       value="<?= htmlspecialchars($oudContactFormulier['voornaam'] ?? '') ?>"
                       required />
            </div>

            <div class="contact-popup__group">
                <label for="achternaam"><?= __('contact.last_name') ?></label>
                <input type="text" id="achternaam" name="achternaam"
                       placeholder="<?= __('contact.last_name') ?>"
                       value="<?= htmlspecialchars($oudContactFormulier['achternaam'] ?? '') ?>"
                       required />
            </div>

            <div class="contact-popup__group">
                <label for="email"><?= __('contact.email') ?></label>
                <input type="email" id="email" name="email"
                       placeholder="email@voorbeeld.nl"
                       value="<?= htmlspecialchars($oudContactFormulier['email'] ?? '') ?>"
                       required />
            </div>

            <div class="contact-popup__group">
                <label for="telefoonnummer">
                    <?= __('contact.phone_num') ?> 
                    <span><?= __('contact.opt') ?></span></label>
                <input type="text" id="telefoonnummer" name="telefoonnummer"
                       placeholder="0123456789"
                       value="<?= htmlspecialchars($oudContactFormulier['telefoonnummer'] ?? '') ?>" />
            </div>

            <div class="contact-popup__group">
                <label for="bericht">
                    <?= __('contact.message') ?>
                </label>
                <textarea id="bericht" name="bericht"
                          placeholder="<?= __('contact.message_ph') ?>"
                          rows="5"
                          maxlength="1000"
                          required><?= htmlspecialchars($oudContactFormulier['bericht'] ?? '') ?></textarea>
            </div>

            <div class="contact-popup__actions">
                <button type="submit" class="dark-button"><?= __('contact.send') ?></button>
            </div>
        </form>

    </div>
</div>
