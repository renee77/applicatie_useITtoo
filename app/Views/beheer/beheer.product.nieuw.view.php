<?php
$currentPage = 'product';
$pageTitle = __('admin_product_overview.page_title');
?>
<h1><?= __('admin_product.title_new') ?></h1>

<form method="POST" action="<?= BASE_URL ?>/beheer/product/nieuw" class="newProductForm">
    <div class="form__group">
      <label class="form__groupLabel" for="naam">
        <?= __('admin_product.name') ?>
      </label>
      <input class="form__groupInput" type="text" id="naam" name="naam" required />
    </div>

    <div class="form__group">
      <label class="form__groupLabel" for="prijs">
        <?= __('admin_product.price') ?>
      </label>
      <input class="form__groupInput" type="number" id="prijs" name="prijs" min="0" step="0.01" required />
    </div>
    
    <div class="form__group">
      <label class="form__groupLabel" for="leverancier">
        <?= __('admin_product.supplier') ?>
      </label>
      <input class="form__groupInput" type="text" id="leverancier" name="leverancier" />
    </div>

    <div class="form__group">
      <label class="form__groupLabel" for="voorraad">
        <?= __('admin_product.stock') ?>
      </label>
      <input class="form__groupInput" type="text" id="voorraad" name="voorraad" required />
    </div>

    <div class="form__group">
      <label class="form__groupLabel" for="verkoop_gewicht">
        <?= __('admin_product.weight') ?>
      </label>
      <input class="form__groupInput" type="text" id="verkoop_gewicht" name="verkoop_gewicht" />
    </div>

    <div class="form__group">
      <label class="form__groupLabel" for="eenheid">
        <?= __('admin_product.unit') ?>
      </label>
        <select name="eenheid" id="eenheid" class="form__groupInput" required>
          <?php foreach ($eenheden as $eenheid) : ?>
            <option value="<?= $eenheid->value ?>">
                <?= $eenheid->value ?>
            </option>
          <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form__group">
      <label class="form__groupLabel" for="foto_url">
        <?= __('admin_product.image_name') ?>
      </label>
      <input class="form__groupInput" type="text" id="foto_url" name="foto_url" />
    </div>

    <div class="form__group">
      <label class="form__groupLabel" for="omschrijving">
        <?= __('admin_product.description') ?>
      </label>
      <textarea id="omschrijving" name="omschrijving" class="form__groupInput" rows="5" required ></textarea>
    </div>

    <input type="submit" value="<?= __('admin_product.save') ?>" class="orangeBtn btn"/>
</form>
