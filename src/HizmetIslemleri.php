// src/HizmetIslemleri.php

namespace App;

use PDO;

class HizmetIslemleri
{
    public static function hizmetSil(PDO $conn, int $id): bool
    {
        // Görseli al
        $stmt = $conn->prepare("SELECT gorsel FROM hizmetler WHERE id = ?");
        $stmt->execute([$id]);
        $gorsel = $stmt->fetchColumn();

        // Veritabanından kaydı sil
        $stmt = $conn->prepare("DELETE FROM hizmetler WHERE id = ?");
        $sonuc = $stmt->execute([$id]);

        // Görsel varsa sil
        if ($sonuc && !empty($gorsel) && file_exists(__DIR__ . '/../images/' . $gorsel)) {
            unlink(__DIR__ . '/../images/' . $gorsel);
        }

        return $sonuc;
    }
}
