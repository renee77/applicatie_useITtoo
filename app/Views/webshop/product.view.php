<?php
/** @var \App\Models\Product $product */

$title = htmlspecialchars($product->getNaam()); ?>

<section id="productBackground">
    <div class="container" id="product">

        <div class="product-header">
            <h1><?= htmlspecialchars($product->getNaam()) ?></h1>
            <p class="product-subtitle">
                <?= $product->getVerkoopGewicht() ?> <?= $product->getEenheid()->value ?>
            </p>
        </div>

        <div class="product-afbeelding">
            <img src="<?= htmlspecialchars(
                $product->getFotoUrl()
                            ? BASE_URL . '/assets/images/products/' . $product->getFotoUrl()
                            : BASE_URL . '/assets/images/products/useITtoo_placeholder.png'
            ) ?>"
                 alt="<?= htmlspecialchars($product->getNaam()) ?>"
                 onerror="this.src='<?= BASE_URL ?>/assets/images/products/useITtoo_placeholder.png'">
            <div class="product-koop">
                <div class="product-prijs-info">
                    <span class="prijs">€<?= number_format($product->getPrijs(), 2, ',', '.') ?></span>
                    <span class="hoeveelheid">
                        <?= $product->getVerkoopGewicht() ?> <?= $product->getEenheid()->value ?>
                    </span>
                </div>
                <button class="orange-button">Koop nu</button>
            </div>
        </div>

        <div class="product-details">
            <?php if ($product->getOmschrijving()) : ?>
                <h3>Beschrijving</h3>
                <p><?= htmlspecialchars($product->getOmschrijving()) ?></p>
            <?php endif; ?>

            <?php if ($product->getLeverancier()) : ?>
                <h3>Herkomst</h3>
                <p><?= htmlspecialchars($product->getLeverancier()) ?></p>
            <?php endif; ?>
        </div>

    </div>
</section>
