<?php
// Hata raporlamayı açın
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Veritabanı bağlantı bilgileri
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "otomasyon_db";

// Veritabanı bağlantısını oluştur
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    // PDO hata modunu ayarla
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // echo "Veritabanı bağlantısı başarılı";
} catch(PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Genel site ayarları için fonksiyon
function getSiteSettings() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM site_ayarlari LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result === false) {
            error_log("site_ayarlari tablosunda veri bulunamadı");
            return null;
        }
        return $result;
    } catch(PDOException $e) {
        error_log("getSiteSettings hatası: " . $e->getMessage());
        return null;
    }
}
// Firma bilgilerini getiren fonksiyon
function getFirmaBilgileri() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM firma_bilgileri WHERE aktif = 1 ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch();
    } catch(PDOException $e) {
        error_log("getFirmaBilgileri hatası: " . $e->getMessage());
        return null;
    }
}
// Bilgi bloklarını getiren fonksiyon
function getBilgiBloklar() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM bilgi_bloklari WHERE aktif = 1 ORDER BY sira ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("getBilgiBloklar hatası: " . $e->getMessage());
        return [];
    }
}
// Firma sahiplerini getiren fonksiyon
function getFirmaSahipleri() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM firma_sahipleri WHERE aktif = 1 ORDER BY sira ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("getFirmaSahipleri hatası: " . $e->getMessage());
        return [];
    }
}
// Hizmetleri getiren fonksiyon
function getHizmetler() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM hizmetler ORDER BY sira ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("getHizmetler hatası: " . $e->getMessage());
        return [];
    }
}

// Footer ayarlarını getiren fonksiyon
function getFooterSettings() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM footer_ayarlari WHERE id = 1 LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result === false) {
            error_log("footer_ayarlari tablosunda veri bulunamadı");
            return null;
        }
        return $result;
    } catch(PDOException $e) {
        error_log("getFooterSettings hatası: " . $e->getMessage());
        return null;
    }
}
// Hizmet bölgelerini getiren fonksiyon
function getHizmetBolgeleri() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM hizmet_bolgeleri WHERE aktif = 1 ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch();
    } catch(PDOException $e) {
        error_log("getHizmetBolgeleri hatası: " . $e->getMessage());
        return null;
    }
}
// Admin girişi kontrol fonksiyonu
function checkAdminLogin($username, $password) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE kullanici_adi = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['sifre'])) {
            return true;
        }
        return false;
    } catch(PDOException $e) {
        error_log("checkAdminLogin hatası: " . $e->getMessage());
        return false;
    }
}

// Site ayarlarını güncelleme fonksiyonu
function updateSiteSettings($telefon, $email, $adres, $facebook, $instagram, $twitter) {
    global $conn;
    try {
        $stmt = $conn->prepare("UPDATE site_ayarlari SET 
            telefon = ?,
            email = ?,
            adres = ?,
            facebook = ?,
            instagram = ?,
            twitter = ?
            WHERE id = 1");
        return $stmt->execute([$telefon, $email, $adres, $facebook, $instagram, $twitter]);
    } catch(PDOException $e) {
        error_log("updateSiteSettings hatası: " . $e->getMessage());
        return false;
    }
}

// Güvenli input temizleme fonksiyonu
function guvenliInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Dosya yükleme fonksiyonu
function dosyaYukle($file, $hedef_klasor, $izin_verilen_uzantilar = ['jpg', 'jpeg', 'png', 'gif']) {
    try {
        if ($file['error'] === 0) {
            $dosya_uzantisi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (in_array($dosya_uzantisi, $izin_verilen_uzantilar)) {
                $yeni_dosya_adi = uniqid() . '.' . $dosya_uzantisi;
                $hedef_yol = $hedef_klasor . $yeni_dosya_adi;
                
                if (move_uploaded_file($file['tmp_name'], $hedef_yol)) {
                    return $yeni_dosya_adi;
                }
            }
        }
        return false;
    } catch(Exception $e) {
        error_log("dosyaYukle hatası: " . $e->getMessage());
        return false;
    }
}

// Oturum kontrolü
function oturumKontrol() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: admin-login.php');
        exit;
    }
}

// Tarih formatı düzenleme fonksiyonu
function tarihFormat($tarih) {
    return date('d.m.Y H:i', strtotime($tarih));
}

// Slug oluşturma fonksiyonu
function createSlug($string) {
    $string = str_replace(['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'], 
                         ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'], $string);
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    $string = trim($string, '-');
    return $string;
}

// Mesaj gösterme fonksiyonu
function showMessage($message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

// Pagination fonksiyonu
function getPagination($tablo, $sayfa_basina, $aktif_sayfa = 1) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) as toplam FROM $tablo");
        $stmt->execute();
        $sonuc = $stmt->fetch();
        $toplam_kayit = $sonuc['toplam'];
        
        $toplam_sayfa = ceil($toplam_kayit / $sayfa_basina);
        $baslangic = ($aktif_sayfa - 1) * $sayfa_basina;
        
        return [
            'toplam_kayit' => $toplam_kayit,
            'toplam_sayfa' => $toplam_sayfa,
            'baslangic' => $baslangic,
            'aktif_sayfa' => $aktif_sayfa
        ];
    } catch(PDOException $e) {
        error_log("getPagination hatası: " . $e->getMessage());
        return false;
    }
}

// Admin oturum kontrolü
function adminOturumKontrol() {
    // Oturum başlamamışsa başlat
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Admin girişi yapılmamışsa login sayfasına yönlendir
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        // Mevcut URL'yi al
        $requested_page = $_SERVER['REQUEST_URI'];
        
        // Giriş sayfasına yönlendir ve hangi sayfaya erişilmek istendiğini parametre olarak ekle
        header('Location: admin-login.php?redirect=' . urlencode($requested_page));
        exit;
    }
}
?>