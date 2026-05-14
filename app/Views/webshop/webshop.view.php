<?php
/** @var \App\Models\Product[] $groenten */
/** @var \App\Models\Product[] $fruit */
/** @var \App\Models\Product[] $houdbaar */

$title = 'Webwinkel'; ?>

<p><?= BASE_URL . '/public/assets/images/products/placeholder.jpg' ?></p>

<link rel="stylesheet" href="/css/webshop.css">

<section id="salesBackground">
    <div class="container" id="sales">
        <div class="sale-block">
            <span class="sale-label">Aanbieding</span>
            <p>Aanbieding 1 dummy</p>
        </div>
        <div class="sale-block">
            <span class="sale-label">Aanbieding</span>
            <p>Aanbieding 2 dummy</p>
        </div>
    </div>
</section>

<section id="shopBackground">
    <div class="container" id="shop">
        <h1>Onze Producten</h1>

        <div class="product_categorie">
            <h2>Groente</h2>
            <div class="product-grid">
                <?php foreach ($groenten as $product) : ?>
                    <div class="product-tile">
                        <img src="<?= htmlspecialchars($product->getFotoUrl() ??
                                    BASE_URL . '/assets/images/products/placeholder.jpg') ?>"
                             alt="<?= htmlspecialchars($product->getNaam()) ?>">
                        <h3><?= htmlspecialchars($product->getNaam()) ?></h3>
                        <p class="prijs">€<?= number_format($product->getPrijs(), 2, ',', '.') ?> / 
                           <?= $product->getVerkoopGewicht() ?> <?= $product->getEenheid()->value ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="product_categorie">
            <h2>Fruit</h2>
            <div class="product-grid">
                <?php foreach ($fruit as $product) : ?>
                    <div class="product-tile">
                        <img src="<?= htmlspecialchars($product->getFotoUrl() ??
                                    BASE_URL . '/assets/images/products/placeholder.jpg') ?>"
                             alt="<?= htmlspecialchars($product->getNaam()) ?>">
                        <h3><?= htmlspecialchars($product->getNaam()) ?></h3>
                        <p class="prijs">€<?= number_format($product->getPrijs(), 2, ',', '.') ?> / 
                           <?= $product->getVerkoopGewicht() ?> <?= $product->getEenheid()->value ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="product_categorie">
            <h2>Langerhoudbaar</h2>
            <div class="product-grid">
                <?php foreach ($houdbaar as $product) : ?>
                    <div class="product-tile">
                        <img src="<?= htmlspecialchars($product->getFotoUrl() ??
                                    BASE_URL . '/assets/images/products/placeholder.jpg') ?>"
                             alt="<?= htmlspecialchars($product->getNaam()) ?>">
                        <h3><?= htmlspecialchars($product->getNaam()) ?></h3>
                        <p class="prijs">€<?= number_format($product->getPrijs(), 2, ',', '.') ?> / 
                           <?= $product->getVerkoopGewicht() ?> <?= $product->getEenheid()->value ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>