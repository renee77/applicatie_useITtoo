<?php

namespace App\DAO;

use App\Models\Product;
use App\Models\Eenheid;
use PDO;

use function PHPUnit\Framework\throwException;

class ProductDAO
{
    // De PDO verbinding wordt bewaard als property
    // zodat alle methoden er gebruik van kunnen maken
    private PDO $db;

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
        // Dit is ook veiliger dan de variabele direct in de SQL te zetten, want PDO zorgt dat de waarde niet als SQL geïnterpreteerd wordt — dit voorkomt SQL injection.
        $sql = "SELECT * FROM product WHERE product_id = :product_id AND deleted_at IS NULL";

        // query voorbereiden en parameter binden
        // $db is een PDO prepare is methode van PDO klasse
        // $db->prepare()   // query voorbereiden
        // $db->query()     // directe query uitvoeren
        // $db->quote()     // waarde escapen
        $stmt = $this->db->prepare($sql);
        // prepare() geeft een PDOStatement object terug — dat is $stmt. Ook dat is een ingebouwde PHP klasse met eigen methoden:
        // $stmt->bindValue()  // waarde koppelen aan placeholder
        //      De beschikbare type constanten zijn:
        //      PDO::PARAM_INT → geheel getal
        //      PDO::PARAM_STR → tekst
        //      PDO::PARAM_BOOL → boolean
        //      PDO::PARAM_NULL → null
        // $stmt->execute()    // query uitvoeren
        // $stmt->fetch()      // één rij ophalen
        // $stmt->fetchAll()   // alle rijen ophalen
        $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);

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
            $row['foto_url']
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
                $row['foto_url']
            );
        }

        return $products;
    }

    public function addProduct(Product $product): void
    {
        $sql = "INSERT INTO product(naam, prijs, verkoop_gewicht, eenheid, omschrijving, leverancier, foto_url) 
                VALUES (:naam, :prijs, :verkoop_gewicht, :eenheid, :omschrijving, :leverancier, :foto_url)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':naam', $product->getNaam(), PDO::PARAM_STR);
        $stmt->bindValue(':prijs', $product->getPrijs(), PDO::PARAM_STR);
        $stmt->bindValue(':verkoop_gewicht', $product->getVerkoopGewicht(), PDO::PARAM_STR);
        $stmt->bindValue(':eenheid', $product->getEenheid()->value, PDO::PARAM_STR);
        $stmt->bindValue(':omschrijving', $product->getOmschrijving(), $product->getOmschrijving() === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':leverancier', $product->getLeverancier(), $product->getLeverancier() === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':foto_url', $product->getFotoUrl(), $product->getFotoUrl() === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->execute();
    }

    public function updateProduct(Product $product): void
    {

    }

    public function deleteProduct(int $product_id): void
    {

    }
    /** @return Product[] */
    public function getDeletedProductByNaam(string $naam): array
    {

    }

    public function restoreProduct(int $product_id): void
    {

    }
}
