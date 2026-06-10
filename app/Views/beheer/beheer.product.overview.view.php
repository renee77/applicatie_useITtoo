<?php
$melding = $session->getMelding();
$fout = $session->getFout();
$currentPage = 'product';
$pageTitle = __('admin_product_overview.page_title');
?>
<?php if (!empty($melding)) : ?>
    <div class="melding">
        <?= htmlspecialchars($melding) ?>
    </div>
<?php endif; ?>
<?php if (!empty($fout)) : ?>
    <div class="fout">
        <?= htmlspecialchars($fout) ?>
    </div>
<?php endif; ?>
  <h1><?= __('admin_product_overview.title') ?></h1>
  <a href="<?= BASE_URL ?>/beheer/product/nieuw" class="orangeBtn toNewLink">
    <?= __('admin_product_overview.new_product') ?>
  </a>
  <form id="search" method="GET" action="<?= BASE_URL ?>/beheer/product">
    <input name="zoekterm" type="text" value="<?= htmlspecialchars($zoekterm ?? '') ?>" 
    placeholder="<?= __('admin_product_overview.type_name') ?>" class="searchbar">
    <input type="Submit" value="Zoeken" class="orangeBtn btn" />
  </form>

  <table>
    <thead>
      <th class="tableName">
        <?= __('admin_product_overview.name') ?>
      </th>
      <th class="tablePrice">
        <?= __('admin_product_overview.price') ?>
      </th>
      <th class="tableSupplier">
        <?= __('admin_product_overview.supplier') ?>
      </th>
      <th class="tableWeight"><?= __('admin_product_overview.weight') ?></th>
      <th class="tableDescription"><?= __('admin_product_overview.description') ?></th>
      <th class="tableLink"><?= __('admin_product_overview.edit_label') ?></th>
    </thead>
    <tbody>
    <?php foreach ($products as $product) : ?>
      <tr>
        <td><?= htmlspecialchars($product->getNaam()) ?></td>
        <td>€ <?= number_format($product->getPrijs(), 2, ',', '.') ?></td>
        <td><?= htmlspecialchars($product->getLeverancier() ?? '—') ?></td>
        <td><?= $product->getVerkoopGewicht() ?> <?= $product->getEenheid()->value ?></td>
        <td><?= htmlspecialchars($product->getOmschrijving() ?? '—') ?></td>
        <td>
          <a class="orangeBtn changesBtn" 
          href="<?= BASE_URL ?>/beheer/product/edit?id=<?= $product->getId() ?>">
          <?= __('admin_product_overview.edit') ?>
        </a>
          <form method="POST" action="<?= BASE_URL ?>/beheer/product/delete"
          class="deleteBtn changesBtn">
            <input type="hidden" name="id" value="<?= $product->getId() ?>">
            <button type="submit" class="deleteBtn changesBtn" 
                    onclick="return confirm(
                    '<?= __('admin_product_overview.delete_confirm') ?>
                    <?= htmlspecialchars($product->getNaam()) ?>')">
                <?= __('admin_product_overview.delete') ?>
            </button>
        </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  </table>
