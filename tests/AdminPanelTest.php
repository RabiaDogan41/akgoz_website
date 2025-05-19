<?php
use PHPUnit\Framework\TestCase;

class AdminPanelTest extends TestCase
{
    protected $conn;

    protected function setUp(): void
    {
        // config dosyasını include et
        require __DIR__ . '/../config/config.php';
        $this->conn = $conn;
    }

    public function testHizmetEkleme()
    {
        $baslik = 'Test Başlık';
        $icerik = 'Test İçerik';
        $aktif = 1;
        $gorsel = 'test.jpg';

        $stmt = $this->conn->prepare("INSERT INTO hizmetler (baslik, icerik, aktif, gorsel) VALUES (?, ?, ?, ?)");
        $sonuc = $stmt->execute([$baslik, $icerik, $aktif, $gorsel]);

        $this->assertTrue($sonuc);

        // Kayıt gerçekten eklendi mi kontrol
        $stmt = $this->conn->prepare("SELECT * FROM hizmetler WHERE baslik = ?");
        $stmt->execute([$baslik]);
        $kayit = $stmt->fetch();

        $this->assertNotEmpty($kayit);

        // Temizlik
        $this->conn->prepare("DELETE FROM hizmetler WHERE baslik = ?")->execute([$baslik]);
    }

    public function testHizmetSilme()
    {
        // Önce ekleme
        $stmt = $this->conn->prepare("INSERT INTO hizmetler (baslik, icerik, aktif, gorsel) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Silinecek', 'Silinecek içerik', 1, 'sil.jpg']);
        $id = $this->conn->lastInsertId();

        // Silme işlemi
        $stmt = $this->conn->prepare("DELETE FROM hizmetler WHERE id = ?");
        $sonuc = $stmt->execute([$id]);

        $this->assertTrue($sonuc);

        // Kontrol
        $stmt = $this->conn->prepare("SELECT * FROM hizmetler WHERE id = ?");
        $stmt->execute([$id]);
        $this->assertFalse($stmt->fetch());
    }
}
