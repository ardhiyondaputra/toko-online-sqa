<?php

use PHPUnit\Framework\TestCase;
use App\Checkout;

require_once __DIR__ . '/../vendor/autoload.php';

class CheckoutTest extends TestCase
{
    private $seedFile;
    private $testFile;
    private $orderFile;
    private $checkout;

    protected function setUp(): void
    {
        $this->seedFile = __DIR__ . '/../data/products_seed.json';
        $this->testFile = __DIR__ . '/../data/products_test.json';
        $this->orderFile = __DIR__ . '/../data/orders_test.json';

        copy($this->seedFile, $this->testFile);

        file_put_contents(
            $this->orderFile,
            json_encode([])
        );

        $this->checkout = new Checkout(
            $this->testFile,
            $this->orderFile
        );
    }

    public function testCheckoutReducesStock()
    {
        $keranjang = [
            'PRD-002' => 1
        ];

        $this->checkout->prosesCheckout(
            'test@mail.com',
            'Jl. Sudirman',
            $keranjang
        );

        $products = json_decode(
            file_get_contents($this->testFile),
            true
        );

        $this->assertEquals(
            4,
            $products['PRD-002']['stok']
        );
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testFile)) {
            unlink($this->testFile);
        }

        if (file_exists($this->orderFile)) {
            unlink($this->orderFile);
        }
    }
}