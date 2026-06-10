<?php
/** @var \App\Models\Product[] $groenten */
/** @var \App\Models\Product[] $fruit */
/** @var \App\Models\Product[] $houdbaar */

$title = __('webshop.title'); ?>

<section id="salesBackground">
    <div class="container" id="sales">
        <div class="sale-block">
            <span class="sale-label"><?= __('webshop.sale') ?></span>
            <p><?= __('webshop.sale_dummy') ?></p>
        </div>
        <div class="sale-block">
            <span class="sale-label">
                <?= __('webshop.sale') ?></span>
            <p><?= __('webshop.sale_dummy') ?></p>
        </div>
    </div>
</section>

<section id="shopBackground">
    <div class="container" id="shop">
        <h1><?= __('webshop.our_products') ?></h1>

        <div class="product_categorie">
            <h2><?= __('webshop.vegetables') ?></h2>
            <div class="product-grid">
                <?php foreach ($groenten as $product) : ?>
                    <?php require __DIR__ . '/_product_tile.view.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="product_categorie">
            <h2><?= __('webshop.fruit') ?></h2>
            <div class="product-grid">
                <?php foreach ($fruit as $product) : ?>
                    <?php require __DIR__ . '/_product_tile.view.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="product_categorie">
            <h2><?= __('webshop.longer_shelf_life') ?></h2>
            <div class="product-grid">
                <?php foreach ($houdbaar as $product) : ?>
                    <?php require __DIR__ . '/_product_tile.view.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>
