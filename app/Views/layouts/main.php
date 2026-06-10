<?php
$error = $session->getFout();
$contactFout = $session->getContactFout();
$oudContactFormulier = $session->getInvoerFormulier();
$melding = $session->getMelding();
?>
<?php include __DIR__ . '/header.view.php'; ?>
<?php include __DIR__ . '/../webshop/klant.login.view.php'; ?>
<?php include __DIR__ . '/../webshop/contact.view.php'; ?>
<main>
    <?php if (!empty($melding)) : ?>
        <div class="melding-banner" id="meldingBanner">
            <div class="container">
                <p><?= htmlspecialchars($melding) ?></p>
                <button class="melding-banner__sluit" aria-label="Sluiten">&#x2715;</button>
            </div>
        </div>
    <?php endif; ?>
    <div class="container">
    <?= $content ?? '' ?>
    </div>
</main>
<?php include __DIR__ . '/footer.view.php'; ?>