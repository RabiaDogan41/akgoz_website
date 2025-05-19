<?php

// Hata raporlamayı açın
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Config dosyasını dahil edelim
require_once '../config/config.php';
// Oturum başlat
session_start();

// Oturum kontrolü
adminOturumKontrol();


// Site ayarlarını güncelleme
if (isset($_POST['ayarlari_guncelle'])) {
    $telefon1 = $_POST['telefon1'];
    $telefon2 = $_POST['telefon2'];
    $ust_mesaj = $_POST['ust_mesaj'];
    
    try {
        $sql = "UPDATE site_ayarlari SET telefon1 = ?, telefon2 = ?, ust_mesaj = ? WHERE id = 1";
        $stmt = $conn->prepare($sql);
        $sonuc = $stmt->execute([$telefon1, $telefon2, $ust_mesaj]);
        
        if ($sonuc) {
            $basari_mesaji = "Site ayarları başarıyla güncellendi!";
        } else {
            $hata_mesaji = "Güncelleme sırasında bir hata oluştu.";
        }
    } catch (PDOException $e) {
        $hata_mesaji = "Veritabanı hatası: " . $e->getMessage();
    }
}

// Site ayarlarını getir
try {
    $stmt = $conn->prepare("SELECT * FROM site_ayarlari WHERE id = 1 LIMIT 1");
    $stmt->execute();
    $site_ayarlari = $stmt->fetch();
    
    if (!$site_ayarlari) {
        // Eğer kayıt yoksa varsayılan değerlerle oluştur
        $stmt = $conn->prepare("INSERT INTO site_ayarlari (telefon1, telefon2, ust_mesaj) VALUES (?, ?, ?)");
        $stmt->execute(['+90 555 123 4567', '+90 555 765 4321', 'Bizimle iletişime geçin: {telefon1} | {telefon2}']);
        
        // Yeni kaydı getir
        $stmt = $conn->prepare("SELECT * FROM site_ayarlari WHERE id = 1 LIMIT 1");
        $stmt->execute();
        $site_ayarlari = $stmt->fetch();
    }
} catch (PDOException $e) {
    $hata_mesaji = "Veritabanı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Site Ayarları</title>
    <link rel="stylesheet" href="../style/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-logo">
                <h2>Admin Panel</h2>
            </div>
            <ul class="admin-menu">
    <li><a href="admin.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'class="active"' : ''; ?>><i class="fas fa-cogs"></i> Hizmet Yönetimi</a></li>
    <li><a href="admin-hizmet-bolgeleri.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin-hizmet-bolgeleri.php' ? 'class="active"' : ''; ?>><i class="fas fa-map-marker-alt"></i> Hizmet Bölgeleri</a></li>
    <li><a href="admin-firma-bilgileri.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin-firma-bilgileri.php' ? 'class="active"' : ''; ?>><i class="fas fa-building"></i> Firma Bilgileri</a></li>
    <li><a href="admin-bilgi-bloklari.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin-bilgi-bloklari.php' ? 'class="active"' : ''; ?>><i class="fas fa-info-circle"></i> Bilgi Blokları</a></li>
    <li><a href="admin-firma-sahipleri.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin-firma-sahipleri.php' ? 'class="active"' : ''; ?>><i class="fas fa-user-tie"></i> Firma Sahipleri</a></li>
    <li><a href="admin-site-ayarlari.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin-site-ayarlari.php' ? 'class="active"' : ''; ?>><i class="fas fa-wrench"></i> Üst Bilgi Ayarları</a></li>
    <li><a href="admin-footer-ayarlari.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin-footer-ayarlari.php' ? 'class="active"' : ''; ?>><i class="fas fa-shoe-prints"></i> Footer Ayarları</a></li>
    <li><a href="admin-logout.php"><i class="fas fa-sign-out-alt"></i> Çıkış</a></li>
</ul>
        </div>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Üst Bilgi Ayarları</h1>
                
            </div>
            
            <?php if (isset($basari_mesaji)): ?>
            <div class="alert success"><?php echo $basari_mesaji; ?></div>
            <?php endif; ?>
            
            <?php if (isset($hata_mesaji) && !empty($hata_mesaji)): ?>
            <div class="alert error"><?php echo $hata_mesaji; ?></div>
            <?php endif; ?>
            
            <div class="admin-box">
                <h2>Genel Ayarlar</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="telefon1">Telefon 1:</label>
                        <input type="text" id="telefon1" name="telefon1" value="<?php echo isset($site_ayarlari['telefon1']) ? $site_ayarlari['telefon1'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefon2">Telefon 2:</label>
                        <input type="text" id="telefon2" name="telefon2" value="<?php echo isset($site_ayarlari['telefon2']) ? $site_ayarlari['telefon2'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ust_mesaj">Üst Mesaj:</label>
                        <input type="text" id="ust_mesaj" name="ust_mesaj" value="<?php echo isset($site_ayarlari['ust_mesaj']) ? $site_ayarlari['ust_mesaj'] : ''; ?>" required>
                        <small>Mesaj içinde {telefon1} ve {telefon2} yer tutucularını kullanabilirsiniz. Örnek: Bizimle iletişime geçin: {telefon1} | {telefon2}</small>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" name="ayarlari_guncelle" class="btn btn-primary">
                            <i class="fas fa-save"></i> Ayarları Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
    // Mobil Menü Toggle Fonksiyonu
    document.addEventListener('DOMContentLoaded', function() {
        // HTML yapısına menü toggle ve overlay ekleyelim
        const body = document.querySelector('body');
        
        // Menü toggle butonu ekle
        const menuToggle = document.createElement('div');
        menuToggle.className = 'menu-toggle';
        menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
        body.appendChild(menuToggle);
        
        // Overlay ekle
        const overlay = document.createElement('div');
        overlay.className = 'overlay';
        body.appendChild(overlay);
        
        // Sidebar ve overlay değişkenleri
        const sidebar = document.querySelector('.admin-sidebar');
        
        // Sayfa yüklendiğinde sidebar'ın doğru durumunu ayarla
        function adjustSidebar() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
            }
        }
        
        // Sayfa yüklendiğinde ve boyut değiştiğinde sidebar'ı ayarla
        adjustSidebar();
        window.addEventListener('resize', adjustSidebar);
        
        // Menü toggle olayı
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            body.classList.toggle('menu-open');
            
            // Menü icon değiştir
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Overlay tıklama olayı
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            body.classList.remove('menu-open');
            
            // Menü ikonunu sıfırla
            const icon = menuToggle.querySelector('i');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        });
        
        // Admin menü linklerine tıklandığında (mobil görünümde menüyü kapat)
        const menuLinks = document.querySelectorAll('.admin-menu a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    setTimeout(function() {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                        body.classList.remove('menu-open');
                        
                        // Menü ikonunu sıfırla
                        const icon = menuToggle.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }, 100); // Biraz gecikme ekleyerek link tıklamasının işlenmesini sağla
                }
            });
        });
    });
</script>
</body>
</html>