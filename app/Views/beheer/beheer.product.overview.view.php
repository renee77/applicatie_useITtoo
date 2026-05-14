  <h1>Alle Producten</h1>
  <button href="#" class="light-button newBtn">
    Nieuw Product aanmaken
  </button>
  <div id="search">
    <input type="text" placeholder="Typ de productnaam in." class="searchbar">
    <input type="Submit" value="Zoeken" class="orangeBtn btn" />
  </div>

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
          <button class="orangeBtn changesBtn">Edit</button>
          <button class="deleteBtn changesBtn">Delete</button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  </table>
</main>
</body>
</html>
