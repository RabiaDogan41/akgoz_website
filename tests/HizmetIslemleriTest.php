// tests/HizmetIslemleriTest.php

use PHPUnit\Framework\TestCase;
use App\HizmetIslemleri;

class HizmetIslemleriTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Test için bir in-memory SQLite veritabanı kullan (gerçek DB yerine)
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
        // Sahte görsel dosyası oluştur
        $gorselYolu = __DIR__ . '/../images/ornek.jpg';
        if (!file_exists(dirname($gorselYolu))) {
            mkdir(dirname($gorselYolu), 0777, true);
        }
        file_put_contents($gorselYolu, 'test verisi');

        $sonuc = HizmetIslemleri::hizmetSil($this->conn, 1);
        $this->assertTrue($sonuc);

        // Kaydın silindiğini kontrol et
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM hizmetler WHERE id = 1");
        $stmt->execute();
        $this->assertEquals(0, $stmt->fetchColumn());

        // Görsel dosyasının silinip silinmediğini kontrol et
        $this->assertFileDoesNotExist($gorselYolu);
    }
}
    