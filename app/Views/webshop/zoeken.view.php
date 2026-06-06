<?php
$title = 'Zoekpagina';

/** @var \App\Models\Product[] $producten */

$melding = $session->getMelding();
?>

<section id="zoekresultatenBackground">
    <div id="zoekresultaten" class="container">
        <?php if (!empty($melding)) : ?>
            <div class="melding"><?= htmlspecialchars($melding) ?></div>
        <?php endif; ?>
        <?php if (!empty($producten)) : ?>
            <?php foreach ($producten as $product) : ?>
                <a href="<?= BASE_URL ?>/webshop/<?= $product->getId() ?>-<?=
                    strtolower(str_replace(' ', '-', $product->getNaam())) ?>"
                    class="zoekresultaat-tegel">
                    <?php $imgUrl = htmlspecialchars(
                        $product->getFotoUrl()
                            ? BASE_URL . '/assets/images/products/' . $product->getFotoUrl()
                            : BASE_URL . '/assets/images/products/useITtoo_placeholder.png'
                    ); ?>
                    <img src="<?= $imgUrl ?>"
                        alt="<?= htmlspecialchars($product->getNaam()) ?>"
                        onerror="this.src='<?= BASE_URL ?>/assets/images/products/useITtoo_placeholder.png'">
                    <div class="zoekresultaat-info">
                        <h3><?= htmlspecialchars($product->getNaam()) ?></h3>
                        <p class="omschrijving"><?= htmlspecialchars($product->getOmschrijving() ?? '') ?></p>
                    </div>
                    <div class="zoekresultaat-prijs">
                        <span class="prijs">€<?= number_format($product->getPrijs(), 2, ',', '.') ?></span>
                        <span class="hoeveelheid"><?= $product->getVerkoopGewicht() ?> 
                            <?= $product->getEenheid()->value ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
