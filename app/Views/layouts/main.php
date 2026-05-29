<?php $error = $session->getFout();?>
<?php include __DIR__ . '/header.view.php'; ?>
<?php include __DIR__ . '/../webshop/klant.login.view.php'; ?>
<main>
    <div class="container">
    <?= $content ?? '' ?>
    </div>
</main>
<?php include __DIR__ . '/footer.view.php'; ?>