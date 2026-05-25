<h1>Product bewerken</h1>

<form method="POST" action="<?= BASE_URL ?>/beheer/product/edit?id=<?= $product->getId() ?>">
    <div class="form__group">
        <label class="form__groupLabel" for="naam">Naam:</label>
        <input class="form__groupInput" type="text" id="naam" name="naam" 
               value="<?= trim(htmlspecialchars($product->getNaam())) ?>" required />
    </div>

    <div class="form__group">
        <label class="form__groupLabel" for="prijs">Prijs:</label>
        <input class="form__groupInput" type="number" id="prijs" name="prijs" 
               value="<?= trim($product->getPrijs()) ?>" step="0.01" min="0" required />
    </div>

    <div class="form__group">
        <label class="form__groupLabel" for="leverancier">Leverancier:</label>
        <input class="form__groupInput" type="text" id="leverancier" name="leverancier" 
               value="<?= trim(htmlspecialchars($product->getLeverancier()) ?? '') ?>" />
    </div>

    <div class="form__group">
        <label class="form__groupLabel" for="gewicht">Gewicht:</label>
        <input class="form__groupInput" type="number" id="gewicht" name="gewicht" 
               value="<?= trim($product->getVerkoopGewicht()) ?>" step="0.01" min="0" />
    </div>

    <div class="form__group">
        <label class="form__groupLabel">Eenheid:</label>
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
        <label class="form__groupLabel" for="omschrijving">Omschrijving:</label>
        <textarea id="omschrijving" name="omschrijving" class="form__groupInput" rows="5"><?=
        htmlspecialchars($product->getOmschrijving()) ?? '' ?></textarea>
    </div>

    <input type="submit" value="Opslaan" class="orangeBtn" />
    <a href="<?= BASE_URL ?>/beheer/product" class="orangeBtn cancelBtn">Annuleren</a>
</form>
