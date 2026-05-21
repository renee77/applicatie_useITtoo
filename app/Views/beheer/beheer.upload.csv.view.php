<h1>Upload hier je CSV-bestand</h1>
<h3>Belangrijke informatie!</h3>
<p>Om het CSV-bestand goed te kunnen verwerken, moet de tekst in het bestand op de volgende manier worden ingestuurd:</p>

<pre>
  naam,prijs,verkoop_gewicht,eenheid,omschrijving,leverancier,foto_url
  Wortel,1.95,1,kg,Verse wortels, Boer Koen uit Oudenbosch,wortel.jpg
  Appel,1.49,2,kg,Verse appels, Boer Anna uit Breda,appel.jpg
</pre>

<p>Als dit niet op deze manier wordt geupload, <strong>wordt het bestand niet verwerkt!</strong></p>

<!--enctype multipart/form-data is nodig om bestanden via post te versturen. Anders kunnen de bestanden NIET worden verzonden. -->
<form method="POST" 
action="<?= BASE_URL ?>/beheer/upload/csv"
enctype="multipart/form-data"
class="csv-form">
  <input type="file" name="csv_bestand" accept=".csv" class="csv-input" required>
  <button type="submit" class="orangeBtn uploadBtn">Uploaden</button>
</form>