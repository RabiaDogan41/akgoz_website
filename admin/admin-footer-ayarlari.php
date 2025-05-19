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


// Footer ayarlarını getir
try {
    $stmt = $conn->prepare("SELECT * FROM footer_ayarlari WHERE id = 1 LIMIT 1");
    $stmt->execute();
    $footer_ayarlari = $stmt->fetch();
    
    if (!$footer_ayarlari) {
        // Eğer kayıt yoksa varsayılan değerlerle oluştur
        $stmt = $conn->prepare("INSERT INTO footer_ayarlari (adres, email, footer_harita) VALUES (?, ?, ?)");
        $stmt->execute(['Gebze, Kocaeli, Türkiye', 'info@firmam.com', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d48275.51271729952!2d29.3877658!3d40.7929888!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14cb248edf428443%3A0xbdbe31aad94fe6bf!2zR2ViemUvS29jYWVsaQ!5e0!3m2!1str!2str!4v1683274234555!5m2!1str!2str" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>']);
        
        // Yeni kaydı getir
        $stmt = $conn->prepare("SELECT * FROM footer_ayarlari WHERE id = 1 LIMIT 1");
        $stmt->execute();
        $footer_ayarlari = $stmt->fetch();
    }
} catch (PDOException $e) {
    $hata_mesaji = "Veritabanı hatası: " . $e->getMessage();
}

// Footer ayarlarını güncelleme
if (isset($_POST['ayarlari_guncelle'])) {
    $adres = $_POST['adres'];
    $email = $_POST['email'];
    $footer_harita = $_POST['footer_harita'];
    
    try {
        $sql = "UPDATE footer_ayarlari SET adres = ?, email = ?, footer_harita = ? WHERE id = 1";
        $stmt = $conn->prepare($sql);
        $sonuc = $stmt->execute([$adres, $email, $footer_harita]);
        
        if ($sonuc) {
            $basari_mesaji = "Footer ayarları başarıyla güncellendi!";
            
            // Güncellenmiş verileri getir
            $stmt = $conn->prepare("SELECT * FROM footer_ayarlari WHERE id = 1 LIMIT 1");
            $stmt->execute();
            $footer_ayarlari = $stmt->fetch();
        } else {
            $hata_mesaji = "Güncelleme sırasında bir hata oluştu.";
        }
    } catch (PDOException $e) {
        $hata_mesaji = "Veritabanı hatası: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Footer Ayarları</title>
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
                <h1>Footer Ayarları</h1>
                
            </div>
            
            <?php if (isset($basari_mesaji)): ?>
            <div class="alert success"><?php echo $basari_mesaji; ?></div>
            <?php endif; ?>
            
            <?php if (isset($hata_mesaji) && !empty($hata_mesaji)): ?>
            <div class="alert error"><?php echo $hata_mesaji; ?></div>
            <?php endif; ?>
            
            <div class="admin-box">
                <h2>Footer Ayarları</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="adres">Adres:</label>
                        <textarea id="adres" name="adres" rows="3" required><?php echo isset($footer_ayarlari['adres']) ? $footer_ayarlari['adres'] : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">E-posta Adresi:</label>
                        <input type="email" id="email" name="email" value="<?php echo isset($footer_ayarlari['email']) ? $footer_ayarlari['email'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="footer_harita">Footer Harita Kodu (iframe):</label>
                        <textarea id="footer_harita" name="footer_harita" rows="4"><?php echo isset($footer_ayarlari['footer_harita']) ? $footer_ayarlari['footer_harita'] : ''; ?></textarea>
                        <small>Google Maps iframe kodunu buraya yapıştırın. Örnek: &lt;iframe src="https://www.google.com/maps/embed?..."&gt;&lt;/iframe&gt;</small>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" name="ayarlari_guncelle" class="btn btn-primary">
                            <i class="fas fa-save"></i> Ayarları Güncelle
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="admin-box">
                <h2>Önizleme</h2>
                <?php if (isset($footer_ayarlari['footer_harita']) && !empty($footer_ayarlari['footer_harita'])): ?>
                <div class="map-preview">
                    <?php echo $footer_ayarlari['footer_harita']; ?>
                </div>
                <?php else: ?>
                <div class="no-data">
                    <p>Henüz harita kodu eklenmemiş.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <style>
        .map-preview {
            width: 100%;
            height: 300px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .map-preview iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>

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