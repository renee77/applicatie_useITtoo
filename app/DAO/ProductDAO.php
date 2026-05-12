<?php

namespace App\DAO;

use App\Models\Product;
use App\Models\Eenheid;
use Exception;
use PDO;
use PDOStatement;
use PhpParser\Node\Stmt;
use App\Models\Categorie;

use function PHPUnit\Framework\throwException;

class ProductDAO
{
    // De PDO verbinding wordt bewaard als property
    // zodat alle methoden er gebruik van kunnen maken
    private PDO $db;

    // Constanten voor named placeholders — voorkomt gedupliceerde strings
    // en maakt refactoring makkelijker als de kolomnaam ooit wijzigt.
    private const PARAM_PRODUCT_ID = ':product_id';
    private const PARAM_NAAM = ':naam';

    // Dependency Injection — de database verbinding wordt
    // van buitenaf meegegeven in plaats van dat de DAO
    // hem zelf ophaalt via Database::getConnection()
    //
    // In productie geef je een echte PDO mee:
    //   $dao = new ProductDAO(Database::getConnection());
    //
    // In tests geef je een nep PDO mee (Mock):
    //   $dao = new ProductDAO($mockPdo);
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getProductById(int $product_id): Product
    {
        // Haal de database verbinding op via Database
        // $db = Database::getConnection(); // statische functie daarom :: geeft een PDO terug

        // Nu $this->db gebruiken in plaats van Database::getConnection()
        // zodat de Mock in tests werkt

        // Schrijf de SQL query
        // De :product_id is een named placeholder. De koppeling naar de PHP variabele maak je daarna via bindValue
        // Dit is ook veiliger dan de variabele direct in de SQL te zetten,
        // want PDO zorgt dat de waarde niet als SQL geïnterpreteerd wordt — dit voorkomt SQL injection.
        $sql = "SELECT * FROM product WHERE product_id = :product_id AND deleted_at IS NULL";

        // query voorbereiden en parameter binden
        // $db is een PDO prepare is methode van PDO klasse
        // $db->prepare()   // query voorbereiden
        // $db->query()     // directe query uitvoeren
        // $db->quote()     // waarde escapen
        $stmt = $this->db->prepare($sql);
        // prepare() geeft een PDOStatement object terug — dat is $stmt.
        // Ook dat is een ingebouwde PHP klasse met eigen methoden:
        // $stmt->bindValue()  // waarde koppelen aan placeholder
        //      De beschikbare type constanten zijn:
        //      PDO::PARAM_INT → geheel getal
        //      PDO::PARAM_STR → tekst
        //      PDO::PARAM_BOOL → boolean
        //      PDO::PARAM_NULL → null
        // $stmt->execute()    // query uitvoeren
        // $stmt->fetch()      // één rij ophalen
        // $stmt->fetchAll()   // alle rijen ophalen
        $stmt->bindValue(self::PARAM_PRODUCT_ID, $product_id, PDO::PARAM_INT);

        // Voer de query uit
        $stmt->execute();

        // Zet het resultaat om naar een Product object
        // $row is een ruwe associatieve array rechtstreeks uit de database
        // $row ziet er zo uit:
        // [
        //     'product_id' => 1,
        //     'naam' => 'Wortel',
        //     'prijs' => 1.95,
        //     ...
        // ]
        $row = $stmt->fetch();
        if ($row === false) {
            throw new \RuntimeException("Product met id $product_id niet gevonden");
        }

        // Return het object
        return new Product(
            $row['naam'],
            $row['prijs'],
            $row['verkoop_gewicht'],
            Eenheid::from($row['eenheid']),
            $row['omschrijving'],
            $row['leverancier'],
            $row['foto_url'],
            null, // deleted_at
            $row['product_id']
        );
    }

    /** @return Product[] */
    public function getAllProducts(): array
    {
        // sql statement
        $sql = "SELECT * FROM `product` WHERE deleted_at IS NULL";

        // het sql statement wordt gecontroleerd en dat beschermt tegen sql injection
        $stmt = $this->db->prepare($sql);
        // de query wordt uitgevoerd
        $stmt->execute();
        // resultaten worden opgehaald
        $rows = $stmt->fetchAll();
        // if ($rows === false) {
        //     throw new \RuntimeException("Fout bij ophalen van producten");
        // } kan niet voorkomen wan ter wordt altijd een array teruggegeven dus false kan niet voorkomen

        $products = [];
        foreach ($rows as $row) {
            $products[] = new Product(
                $row['naam'],
                $row['prijs'],
                $row['verkoop_gewicht'],
                Eenheid::from($row['eenheid']),
                $row['omschrijving'],
                $row['leverancier'],
                $row['foto_url'],
                null, // deleted_at
                $row['product_id']
            );
        }

        return $products;
    }

    // Voegt een nieuw product toe aan de database en geeft het toegekende id terug.
    // Het id wordt door de database bepaald via AUTO_INCREMENT — niet door de applicatie.
    // De DAO vraagt het id op via lastInsertId() zodat de Product klasse
    // zelf geen databaselogica hoeft te bevatten.
    public function addProduct(Product $product): int
    {
        $sql = "INSERT INTO product(naam, prijs, verkoop_gewicht, eenheid, omschrijving, leverancier, foto_url) 
                VALUES (:naam, :prijs, :verkoop_gewicht, :eenheid, :omschrijving, :leverancier, :foto_url)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(self::PARAM_NAAM, $product->getNaam(), PDO::PARAM_STR);
        $stmt->bindValue(':prijs', $product->getPrijs(), PDO::PARAM_STR);
        $stmt->bindValue(':verkoop_gewicht', $product->getVerkoopGewicht(), PDO::PARAM_STR);
        $stmt->bindValue(':eenheid', $product->getEenheid()->value, PDO::PARAM_STR);
        $stmt->bindValue(
            ':omschrijving',
            $product->getOmschrijving(),
            $product->getOmschrijving() === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':leverancier',
            $product->getLeverancier(),
            $product->getLeverancier() === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':foto_url',
            $product->getFotoUrl(),
            $product->getFotoUrl() === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );

        $stmt->execute();

        // Geeft het door de database toegekende AUTO_INCREMENT id terug als integer
        return (int) $this->db->lastInsertId();
    }

    public function updateProduct(Product $product): void
    {
        $product_id = $product->getId();
        // id kan null zijn als het nog niet in de database bestaat
        if ($product_id === null) {
            throw new \RuntimeException("Kan product niet updaten zonder id");
        }
        $sql = "UPDATE product SET naam = :naam, prijs = :prijs, verkoop_gewicht = :verkoop_gewicht, 
            eenheid = :eenheid, omschrijving = :omschrijving, leverancier = :leverancier, 
            foto_url = :foto_url WHERE product_id = :product_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(self::PARAM_NAAM, $product->getNaam(), PDO::PARAM_STR);
        $stmt->bindValue(':prijs', $product->getPrijs(), PDO::PARAM_STR);
        $stmt->bindValue(':verkoop_gewicht', $product->getVerkoopGewicht(), PDO::PARAM_STR);
        $stmt->bindValue(':eenheid', $product->getEenheid()->value, PDO::PARAM_STR);
        $stmt->bindValue(
            ':omschrijving',
            $product->getOmschrijving(),
            $product->getOmschrijving() === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':leverancier',
            $product->getLeverancier(),
            $product->getLeverancier() === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':foto_url',
            $product->getFotoUrl(),
            $product->getFotoUrl() === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $stmt->bindValue(self::PARAM_PRODUCT_ID, $product_id, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function deleteProduct(int $product_id): void
    {
        $sql = "UPDATE product SET deleted_at = NOW() WHERE product_id = :product_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(self::PARAM_PRODUCT_ID, $product_id, PDO::PARAM_INT);
        $stmt->execute();

        // rowCount() geeft het aantal rijen terug dat daadwerkelijk gewijzigd is.
        // Als het product niet bestaat of al soft-deleted is, worden er 0 rijen gewijzigd.
        // In dat geval gooien we een RuntimeException zodat de aanroeper weet dat er niets is gebeurd.
        if ($stmt->rowCount() === 0) {
            throw new \RuntimeException("Product met id $product_id niet gevonden of al verwijderd");
        }
    }

    /** @return Product[] */
    public function getDeletedProductsByNaam(string $naam): array
    {
        // LIKE gebruik je zodat je ook op een gedeelte van de naam kunt zoeken
        $sql = "Select * FROM product WHERE deleted_at IS NOT NULL AND naam LIKE :naam";

        $stmt = $this->db->prepare($sql);
        // De % tekens betekenen "alles ervoor en erna" — dus %citroen% vindt ook "Verse citroenen".
        $stmt->bindValue(self::PARAM_NAAM, '%' . $naam . '%', PDO::PARAM_STR);
        $stmt->execute();

        $rows = $stmt->fetchAll();
        $products = [];

        foreach ($rows as $row) {
            $products[] = new Product(
                $row['naam'],
                $row['prijs'],
                $row['verkoop_gewicht'],
                Eenheid::from($row['eenheid']),
                $row['omschrijving'],
                $row['leverancier'],
                $row['foto_url'],
                null, //deleted-at
                $row['product_id']
            );
        }

        return $products;
    }

    public function restoreProduct(int $product_id): void
    {
        $sql = "UPDATE product SET deleted_at = NULL WHERE product_id = :product_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(self::PARAM_PRODUCT_ID, $product_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            throw new \RuntimeException("Geen product gevonden met dit id of product is niet verwijderd");
        }
    }

    /** @return Product[] */
    public function getProductsByCategorie(Categorie $categorie): array
    {
        $sql = "SELECT naam, omschrijving, prijs, verkoop_gewicht, eenheid, product.product_id, leverancier, foto_url 
                FROM product
                INNER JOIN product_categorie ON product.product_id = product_categorie.product_id
                WHERE product.deleted_at IS NULL AND product_categorie.deleted_at IS NULL 
                    AND product_categorie.categorie = :categorie";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':categorie', $categorie->value, PDO::PARAM_STR);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        $products = [];

        foreach ($rows as $row) {
            $products[] = new Product(
                $row['naam'],
                $row['prijs'],
                $row['verkoop_gewicht'],
                Eenheid::from($row['eenheid']),
                $row['omschrijving'],
                $row['leverancier'],
                $row['foto_url'],
                null, //deleted-at
                $row['product_id']
            );
        }

        return $products;
    }
}
