<?php

use PHPUnit\Framework\TestCase;
use App\HizmetIslemleri;

class HizmetIslemleriTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = new PDO('sqlite::memory:');
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->conn->exec("CREATE TABLE hizmetler (
            id INTEGER PRIMARY KEY,
            gorsel TEXT
        )");

        $this->conn->exec("INSERT INTO hizmetler (id, gorsel) VALUES (1, 'ornek.jpg')");
    }

    public function testHizmetSil()
    {
        $gorselYolu = __DIR__ . '/../images/ornek.jpg';
        if (!file_exists(dirname($gorselYolu))) {
            mkdir(dirname($gorselYolu), 0777, true);
        }
        file_put_contents($gorselYolu, 'test verisi');

        $sonuc = HizmetIslemleri::hizmetSil($this->conn, 1);
        $this->assertTrue($sonuc);

        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM hizmetler WHERE id = 1");
        $stmt->execute();
        $this->assertEquals(0, $stmt->fetchColumn());

        $this->assertFileDoesNotExist($gorselYolu);
    }

    protected function tearDown(): void
    {
        $gorselYolu = __DIR__ . '/../images/ornek.jpg';
        if (file_exists($gorselYolu)) {
            unlink($gorselYolu);
        }

        $klasorYolu = dirname($gorselYolu);
        if (is_dir($klasorYolu) && count(glob("$klasorYolu/*")) === 0) {
            rmdir($klasorYolu);
        }
    }
}
