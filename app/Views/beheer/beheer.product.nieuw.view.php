<h1>Nieuw product aanmaken</h1>

<form method="POST" action="<?= BASE_URL ?>/beheer/product" class="newProductForm">
    <div class="form__group">
      <label class="form__groupLabel" for="naam">Naam:</label>
      <input class="form__groupInput" type="text" id="naam" name="naam" required />
    </div>

    <div class="form__group">
      <label class="form__groupLabel" for="prijs">Prijs:</label>
      <input class="form__groupInput" type="number" id="prijs" name="prijs" min="0" required />
    </div>
    
    <div class="form__group">
      <label class="form__groupLabel" for="leverancier">Leverancier:</label>
      <input class="form__groupInput" type="text" id="leverancier" name="leverancier" />
    </div>

    <div class="form__group">
      <label class="form__groupLabel" for="voorraad">Voorraad:</label>
      <input class="form__groupInput" type="text" id="voorraad" name="voorraad" required />
    </div>

    <div class="form__group">
      <label class="form__groupLabel" for="gewicht">Gewicht:</label>
      <input class="form__groupInput" type="text" id="gewicht" name="gewicht" />
    </div>

    <div class="form__group">
      <label class="form__groupLabel" for="gewicht">Eenheid:</label>
        <select name="eenheid" class="form_groupInput" required>
          <?php foreach ($eenheden as $eenheid) : ?>
            <option value="<?= $eenheid->value ?>">
              <?= $eenheid->value ?>
            </option>
          <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form__group">
      <label class="form__groupLabel" for="afbeelding">Afbeeldingnaam:</label>
      <input class="form__groupInput" type="text" id="afbeelding" name="afbeelding" />
    </div>

    <div class="form__group">
      <label class="form__groupLabel" for="omschrijving">Omschrijving:</label>
      <textarea id="omschrijving" name="omschrijving" class="form__groupInput" rows="5" required ></textarea>
    </div>

    <input type="submit" value="Opslaan" class="orangeBtn btn"/>
</form>
