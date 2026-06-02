<?php

use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;

require_once __DIR__ . '/../vendor/autoload.php';

class SystemTest extends TestCase
{
    private $driver;
    private $baseUrl = 'http://localhost:8000';

    protected function setUp(): void
{
    $host = 'http://127.0.0.1:9515';

    $chromeOptions = new ChromeOptions();
    $chromeOptions->addArguments([
        '--headless=new',
        '--disable-gpu',
        '--no-sandbox',
        '--disable-dev-shm-usage'
    ]);

    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(
        ChromeOptions::CAPABILITY,
        $chromeOptions
    );

    $this->driver = RemoteWebDriver::create(
        $host,
        $capabilities,
        5000,
        5000
    );
}

    public function testHomepageAndSearchFeature()
    {
        $this->driver->get($this->baseUrl);

        $bodyText = $this->driver
            ->findElement(WebDriverBy::tagName('body'))
            ->getText();

        $this->assertStringContainsString(
            'Toko Online',
            $bodyText
        );

        $searchBox = $this->driver
            ->findElement(WebDriverBy::name('cari'));

        $searchBox->sendKeys('Kemeja');
        $searchBox->submit();

        $updatedBodyText = $this->driver
            ->findElement(WebDriverBy::tagName('body'))
            ->getText();

        $this->assertStringContainsString(
            'Kemeja Flanel',
            $updatedBodyText
        );
    }

    protected function tearDown(): void
    {
        if ($this->driver) {
            $this->driver->quit();
        }
    }
}