<?php 
$melding = $session->getMelding(); 
$currentPage = 'product';
$pageTitle = 'Producten';
?>
<?php if (!empty($melding)) : ?>
    <div class="melding">
        <?= htmlspecialchars($melding) ?>
    </div>
<?php endif; ?>
  <h1>Alle Producten</h1>
  <a href="<?= BASE_URL ?>/beheer/product/nieuw" class="orangeBtn toNewLink">
    Nieuw Product aanmaken
  </a>
  <form id="search" method="GET" action="<?= BASE_URL ?>/beheer/product">
    <input name="zoekterm" type="text" value="<?= htmlspecialchars($zoekterm ?? '') ?>" 
    placeholder="Typ de productnaam in." class="searchbar">
    <input type="Submit" value="Zoeken" class="orangeBtn btn" />
  </form>

  <table>
    <thead>
      <th class="tableName">Naam</th>
      <th class="tablePrice">Prijs</th>
      <th class="tableSupplier">Leverancier</th>
      <th class="tableWeight">Gewicht</th>
      <th class="tableDescription">Omschrijving</th>
      <th class="tableLink">Wijzigen</th>
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
          href="<?= BASE_URL ?>/beheer/product/edit?id=<?= $product->getId() ?>">Edit</a>
          <form method="POST" action="<?= BASE_URL ?>/beheer/product/delete"
          class="deleteBtn changesBtn">
            <input type="hidden" name="id" value="<?= $product->getId() ?>">
            <button type="submit" class="deleteBtn changesBtn" 
                    onclick="return confirm(
                    'Weet je zeker dat je <?= htmlspecialchars($product->getNaam()) ?> wilt verwijderen?')">
                Delete
            </button>
        </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  </table>
</main>
</body>
</html>
