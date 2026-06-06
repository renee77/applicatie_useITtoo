<?php

/** @var \App\Models\Product $product */
$imgUrl = htmlspecialchars(
    $product->getFotoUrl()
        ? BASE_URL . '/assets/images/products/' . $product->getFotoUrl()
        : BASE_URL . '/assets/images/products/useITtoo_placeholder.png'
);
?>
<a href="<?= BASE_URL ?>/webshop/<?= $product->getId() ?>-<?=
    strtolower(str_replace(' ', '-', $product->getNaam())) ?>" class="product-tile-link">
    <div class="product-tile">
        <img src="<?= $imgUrl ?>"
             alt="<?= htmlspecialchars($product->getNaam()) ?>"
             onerror="this.src='<?= BASE_URL ?>/assets/images/products/useITtoo_placeholder.png'">
        <h3><?= htmlspecialchars($product->getNaam()) ?></h3>
        <p class="prijs">€<?= number_format($product->getPrijs(), 2, ',', '.') ?> /
           <?= $product->getVerkoopGewicht() ?> <?= $product->getEenheid()->value ?>
        </p>
    </div>
</a>
