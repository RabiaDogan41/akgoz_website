<?php
namespace App;

class HizmetIslemleri
{
    public static function hizmetSil($conn, $id): bool
    {
        $stmt = $conn->prepare("SELECT gorsel FROM hizmetler WHERE id = ?");
        $stmt->execute([$id]);
        $gorsel = $stmt->fetchColumn();

        $stmt = $conn->prepare("DELETE FROM hizmetler WHERE id = ?");
        $sonuc = $stmt->execute([$id]);

        if ($sonuc && $gorsel && file_exists(__DIR__ . "/../../images/" . $gorsel)) {
            unlink(__DIR__ . "/../../images/" . $gorsel);
        }

        return $sonuc;
    }
}   
