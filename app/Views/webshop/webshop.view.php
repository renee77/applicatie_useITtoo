<?php
/** @var \App\Models\Product[] $groenten */
/** @var \App\Models\Product[] $fruit */
/** @var \App\Models\Product[] $houdbaar */

$title = 'Webwinkel'; ?>

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
                    <?php include __DIR__ . '/_product_tile.view.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="product_categorie">
            <h2>Fruit</h2>
            <div class="product-grid">
                <?php foreach ($fruit as $product) : ?>
                    <?php include __DIR__ . '/_product_tile.view.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="product_categorie">
            <h2>Langerhoudbaar</h2>
            <div class="product-grid">
                <?php foreach ($houdbaar as $product) : ?>
                    <?php include __DIR__ . '/_product_tile.view.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>
