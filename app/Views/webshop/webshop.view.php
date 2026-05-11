<?php $title = 'Webwinkel'; ?>

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

        <?php if (empty($products)) : ?>
            <p>Er zijn momenteel geen producten beschikbaar.</p>
        <?php else : ?>
            <div class="shop">
                <?php foreach ($products as $product) : ?>
                    <div class="product">
                        <h5><?= htmlspecialchars($product['naam']) ?></h5>
                        <img src="<?= htmlspecialchars($product['foto_url'] ?? '/img/placeholder.jpg') ?>"
                             alt="<?= htmlspecialchars($product['naam']) ?>">
                        <p class="price">€<?= number_format($product['prijs'], 2, ',', '.') ?></p>
                        <a href="/webshop/product/<?= $product['id'] ?>" class="btn dark-button">Bekijk product</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>