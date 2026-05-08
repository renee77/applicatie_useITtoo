</main>
<footer>
    <div class="footer container">
        <div id="sitemap">
            <h3>Sitemap</h3>
            <ul>
                <li><a href="/index.php">Home</a></li>
            </ul>
        </div>

        <div id="newletter">
            <h3>Nieuwsbrief</h3>

            <p>Schrijf je in voor onze nieuwsbrief:</p>
            <form id="newsletterForm">
                <label for="newsletterEmail" class="invisible">E-mailadres voor nieuwsbrief</label>
                <input type="email" id="newsletterEmail" placeholder="Emailadres" name="email" required />
                <button class="light-button" id="newsletter" type="submit">Inschrijven</button>
            </form>

        </div>

        <div class="adress">
            <h3>Adres & Contact</h3>
            <address>
                useITtoo<br>
                Hogeschoollaan 1<br>
                1234 AA Breda<br>
                Tel: 012-3456789
            </address>
            <p>Email: <a href="mailto:info@useitall.nl">info@useittoo.nl</a></p>
            <button class="light-button contact">Neem nu contact op</button>
        </div>
    </div>
    <div class="footer-bottom container">
        <img src="<?= BASE_URL ?>/images/licht-logo.png" alt="">
        <p> © 2025, useITall. Alle rechten voorbehouden.</p>
    </div>
</footer>



<div id="popUp-container"></div>

<script src="<?= BASE_URL ?>/script/script.js"></script>

</body>