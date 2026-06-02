<?php

use PHPUnit\Framework\TestCase;
use App\Catalog;

require_once __DIR__ . '/../vendor/autoload.php';

class CatalogTest extends TestCase
{
    private $catalog;
    private $testFile;

    protected function setUp(): void
    {
        $this->testFile = __DIR__ . '/test_products.json';

        $dummyData = [
            "PRD-001" => [
                "nama" => "Kemeja Flanel",
                "harga" => 150000,
                "stok" => 10
            ]
        ];

        file_put_contents(
            $this->testFile,
            json_encode($dummyData)
        );

        $this->catalog = new Catalog($this->testFile);
    }

    public function testSearchProductFound()
    {
        $result = $this->catalog->searchProduct("Kemeja");

        $this->assertCount(99, $result);
    }

    public function testSearchProductEmptyKeyword()
    {
        $result = $this->catalog->searchProduct("");

        $this->assertNotEmpty($result);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testFile)) {
            unlink($this->testFile);
        }
    }
}