<?php

namespace Tests\Unit;

use App\DAO\ProductDAO;
use App\Models\Product;
use Override;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use App\Models\Eenheid;

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

    public function testAddProduct(): void
    {
        $product = new Product('Citroen', 3.23, 250, Eenheid::Gram, 'Mooie grote citroenen nu voor het eerst lokaal gekweekt', 'Het Brabantse Land', null);

        $mockStmt = $this->createMock(PDOStatement::class);
        // Dit zegt: "ik verwacht dat execute() precies één keer aangeroepen wordt". Als dat niet gebeurt faalt de test!
        $mockStmt->expects($this->once())
         ->method('execute')
         ->willReturn(true);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);
        $mockPdo->method('lastInsertId')->willReturn('5'); // string want PDO geeft altijd een string terug

        $productDao = new ProductDAO($mockPdo);

        // act
        $product_id = $productDao->addProduct($product);

        // assert
        $this->assertIsInt($product_id);

    }

    public function testUpdateProduct(): void
    {
        $product = new Product('Citroen', 3.23, 250, Eenheid::Gram, 'Mooie grote citroenen nu voor het eerst lokaal gekweekt', 'Het Brabantse Land', null, null, 1);

        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->expects($this->once())
        ->method('execute')
        ->willReturn(true);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $productDao = new ProductDAO($mockPdo);

        // act
        $productDao->updateProduct($product);

    }

    public function testDeleteProduct(): void
    {
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->expects($this->once())->method('execute')->willReturn(true);
        $mockStmt->method('rowCount')->willReturn(1);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $productDao = new ProductDAO($mockPdo);

        $productDao->deleteProduct(1);
    }

    // sad path getProductById
    public function testGetProductByIdReturnsException(): void
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

    public function testUpdateProductReturnsException(): void
    {
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->method('execute')->willReturn(false);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $productDao = new ProductDAO($mockPdo);

        $this->expectException(\RuntimeException::class);

        $product = new Product('Citroen', 3.23, 250, Eenheid::Gram, 'Mooie grote citroenen nu voor het eerst lokaal gekweekt', 'Het Brabantse Land', null);
        $productDao->updateProduct($product);
    }

    public function testDeleteProductReturnsException(): void
    {
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->expects($this->once())->method('execute')->willReturn(true);
        $mockStmt->method('rowCount')->willReturn(0);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $productDao = new ProductDAO($mockPdo);

        $this->expectException(\RuntimeException::class);

        $productDao->deleteProduct(5);
    }

}
