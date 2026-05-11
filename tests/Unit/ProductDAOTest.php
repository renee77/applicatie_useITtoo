<?php

namespace Tests\Unit;

use App\DAO\ProductDAO;
use App\Models\Product;
use Override;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class ProductDAOTest extends TestCase
{
    // Geen setUp() — de Mock configuratie verschilt per test:
    // Bij de happy path geeft fetch() een array terug
    // Bij de sad path geeft fetch() false terug
    // Daarom wordt de DAO per test aangemaakt met zijn eigen Mock configuratie

    // happy path
    public function testGetProductByIdReturnsProduct(): void
    {
        // arrange
        // stap 1: maak nep PDOStatement aan en vertel wat fetch() teruggeeft
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->method('fetch')->willReturn([
            'naam' => 'Wortel',
            'prijs' => 1.95,
            'verkoop_gewicht' => 2.0,
            'eenheid' => 'kg',
            'omschrijving' => 'lekkere wortels',
            'leverancier' => 'Pietje',
            'foto_url' => null
        ]);

        // stap 2: maak nep PDO aan en vertel dat prepare() de nep statement teruggeeft
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        // stap 3: maak de DAO aan met de nep PDO
        $productDao = new ProductDAO($mockPdo);

        // act

        $product = $productDao->getProductById(1);

        // assert
        // controleert of $product een instantie is van de Product klasse
        $this->assertInstanceOf(Product::class, $product);
    }

    public function testGetAllProducts(): void
    {
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->method('fetchAll')->willReturn([
            [
                'naam' => 'Wortel',
                'prijs' => 1.95,
                'verkoop_gewicht' => 2.0,
                'eenheid' => 'kg',
                'omschrijving' => 'lekkere wortels',
                'leverancier' => 'Pietje',
                'foto_url' => null
            ],
            [
                'naam' => 'Appel',
                'prijs' => 2.50,
                'verkoop_gewicht' => 1.0,
                'eenheid' => 'kg',
                'omschrijving' => 'rode appels',
                'leverancier' => 'Boer Piet',
                'foto_url' => null
            ]
        ]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $productDao = new ProductDAO($mockPdo);

        // act
        $products = $productDao->getAllProducts();

        // assert
        // drie kanten van het zelfde gedrag gecontroleerd vanuit meerdere invalshoeken,
        // het zijn geen drie verschillende scenario's
        $this->assertIsArray($products);
        $this->assertCount(2, $products);
        $this->assertContainsOnlyInstancesOf(Product::class, $products);
    }

    // sad path getProductById
    public function testGetProductByIdReturnsExeption(): void
    {
        // arrange
        // stap 1: maak nep PDOStatement aan en vertel wat fetch() teruggeeft
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->method('fetch')->willReturn(false); // ← geen array maar false

        // stap 2: maak nep PDO aan en vertel dat prepare() de nep statement teruggeeft
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        // stap 3: maak de DAO aan met de nep PDO
        $productDao = new ProductDAO($mockPdo);

        //assert
        $this->expectException(\RuntimeException::class);

        // act
        $productDao->getProductById(999);
    }


}
