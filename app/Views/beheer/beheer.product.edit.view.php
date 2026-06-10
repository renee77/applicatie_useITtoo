<?php
$currentPage = 'product';
$pageTitle = __('admin_product_overview.page_title');
?>
<h1><?= __('admin_product.title_edit') ?></h1>

<form method="POST" action="<?= BASE_URL ?>/beheer/product/edit?id=<?= $product->getId() ?>">
    <div class="form__group">
        <label class="form__groupLabel" for="naam">
            <?= __('admin_product.name') ?>
        </label>
        <input class="form__groupInput" type="text" id="naam" name="naam" 
               value="<?= trim(htmlspecialchars($product->getNaam())) ?>" required />
    </div>

    <div class="form__group">
        <label class="form__groupLabel" for="prijs">
            <?= __('admin_product.price') ?>
        </label>
        <input class="form__groupInput" type="number" id="prijs" name="prijs" 
               value="<?= trim($product->getPrijs()) ?>" step="0.01" min="0" required />
    </div>

    <div class="form__group">
        <label class="form__groupLabel" for="leverancier">
            <?= __('admin_product.supplier') ?>
        </label>
        <input class="form__groupInput" type="text" id="leverancier" name="leverancier" 
               value="<?= trim(htmlspecialchars($product->getLeverancier()) ?? '') ?>" />
    </div>

    <div class="form__group">
        <label class="form__groupLabel" for="gewicht">
            <?= __('admin_product.weight') ?>
        </label>
        <input class="form__groupInput" type="number" id="gewicht" name="gewicht" 
               value="<?= trim($product->getVerkoopGewicht()) ?>" step="0.01" min="0" />
    </div>

    <div class="form__group">
        <label class="form__groupLabel">
            <?= __('admin_product.unit') ?>
        </label>
        <select name="eenheid" class="form__groupInput" required>
            <?php foreach ($eenheden as $eenheid) : ?>
                <option value="<?= $eenheid->value ?>" 
                    <?= $eenheid === $product->getEenheid() ? 'selected' : '' ?>>
                    <?= $eenheid->value ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form__group">
        <label class="form__groupLabel" for="foto_url">
            <?= __('admin_product.image_name') ?>
        </label>
        <input class="form__groupInput" type="text" id="foto_url" name="foto_url" 
               value="<?= trim(htmlspecialchars($product->getFotoUrl()) ?? '') ?>" 
               placeholder="foto.jpg" />
    </div>

    <div class="form__group">
        <label class="form__groupLabel" for="omschrijving">
            <?= __('admin_product.description') ?>
        </label>
        <textarea id="omschrijving" name="omschrijving" class="form__groupInput" rows="5"><?=
        htmlspecialchars($product->getOmschrijving()) ?? '' ?></textarea>
    </div>

    <input type="submit" value="<?= __('admin_product.save') ?>" class="orangeBtn" />
    <a href="<?= BASE_URL ?>/beheer/product" class="orangeBtn cancelBtn"><?= __('admin_product.cancel') ?></a>
</form>
