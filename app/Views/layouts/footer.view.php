<footer>
    <div class="footer container">
        <div id="sitemap">
            <h3>Sitemap</h3>
            <ul>
                <li><a href="<?= BASE_URL ?>/">
                    <?= __('footer.home') ?>
                </a></li>
                <li><a href="<?= BASE_URL ?>/beheer">
                    <?= __('footer.admin_portal') ?>
                </a></li>
                <li><a href="<?= BASE_URL ?>/webshop">
                    <?= __('footer.webshop') ?>
                </a></li>
            </ul>
        </div>

        <div id="newsletter">
            <h3><?= __('footer.newsletter') ?></h3>

            <p><?= __('footer.newsletter_subscribe') ?></p>
            <form id="newsletterForm">
                <label for="newsletterEmail" class="invisible"><?= __('footer.newsletter_email') ?></label>
                <input type="email" id="newsletterEmail" placeholder="Emailadres" name="email" required />
                <button class="light-button" id="newsletter" type="submit">
                    <?= __('footer.subscribe') ?>
                </button>
            </form>

        </div>

        <div class="adress">
            <h3><?= __('footer.address_header') ?></h3>
            <address>
                useITtoo<br>
                Hogeschoollaan 1<br>
                1234 AA Breda<br>
                Tel: 012-3456789
            </address>
            <p>Email: <a href="mailto:info@useittoo.nl">info@useittoo.nl</a></p>
            <button class="light-button contact"><?= __('footer.contact') ?></button>
        </div>
    </div>
    <div class="footer-bottom container">
        <img src="<?= BASE_URL ?>/assets/images/logos/licht-logo.png" alt="">
        <p> <?= __('footer.rights') ?></p>
    </div>
</footer>



<div id="popUp-container"></div>

<script src="<?= BASE_URL ?>/assets/js/script.js"></script>

</body>
