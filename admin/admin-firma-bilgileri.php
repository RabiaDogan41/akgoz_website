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


// Firma bilgisi ekleme işlemi
if (isset($_POST['firma_ekle'])) {
    $baslik = $_POST['baslik'];
    $icerik = $_POST['icerik'];
    $ozellik1_baslik = $_POST['ozellik1_baslik'];
    $ozellik1_aciklama = $_POST['ozellik1_aciklama'];
    $ozellik2_baslik = $_POST['ozellik2_baslik'];
    $ozellik2_aciklama = $_POST['ozellik2_aciklama'];
    $ozellik3_baslik = $_POST['ozellik3_baslik'];
    $ozellik3_aciklama = $_POST['ozellik3_aciklama'];
    $ozellik4_baslik = $_POST['ozellik4_baslik'];
    $ozellik4_aciklama = $_POST['ozellik4_aciklama'];
    $aktif = isset($_POST['aktif']) ? 1 : 0;
    
    // Veritabanına ekleme
    try {
        $sql = "INSERT INTO firma_bilgileri (baslik, icerik, ozellik1_baslik, ozellik1_aciklama, ozellik2_baslik, ozellik2_aciklama, ozellik3_baslik, ozellik3_aciklama, ozellik4_baslik, ozellik4_aciklama, aktif) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $sonuc = $stmt->execute([$baslik, $icerik, $ozellik1_baslik, $ozellik1_aciklama, $ozellik2_baslik, $ozellik2_aciklama, $ozellik3_baslik, $ozellik3_aciklama, $ozellik4_baslik, $ozellik4_aciklama, $aktif]);
        
        if ($sonuc) {
            $basari_mesaji = "Firma bilgileri başarıyla eklendi!";
        } else {
            $hata_mesaji = "Veritabanına eklenirken bir hata oluştu.";
        }
    } catch (PDOException $e) {
        $hata_mesaji = "Veritabanı hatası: " . $e->getMessage();
    }
}

// Firma bilgisi silme işlemi
if (isset($_GET['sil']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    try {
        // Veritabanından kaydı sil
        $stmt = $conn->prepare("DELETE FROM firma_bilgileri WHERE id = ?");
        $sonuc = $stmt->execute([$id]);
        
        if ($sonuc) {
            $basari_mesaji = 'Firma bilgisi başarıyla silindi!';
        } else {
            $hata_mesaji = 'Silme işlemi başarısız oldu.';
        }
    } catch(PDOException $e) {
        $hata_mesaji = 'Veritabanı hatası: ' . $e->getMessage();
    }
    
    // Sayfa yenilensin diye yönlendirme yapalım
    header('Location: admin-firma-bilgileri.php?silindi=1');
    exit;
}

// Silme işlemi tamamlandığında
if (isset($_GET['silindi']) && $_GET['silindi'] == '1') {
    $basari_mesaji = 'Firma bilgisi başarıyla silindi!';
}

// Firma bilgisi güncelleme işlemi
if (isset($_POST['firma_guncelle']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $baslik = $_POST['baslik'];
    $icerik = $_POST['icerik'];
    $ozellik1_baslik = $_POST['ozellik1_baslik'];
    $ozellik1_aciklama = $_POST['ozellik1_aciklama'];
    $ozellik2_baslik = $_POST['ozellik2_baslik'];
    $ozellik2_aciklama = $_POST['ozellik2_aciklama'];
    $ozellik3_baslik = $_POST['ozellik3_baslik'];
    $ozellik3_aciklama = $_POST['ozellik3_aciklama'];
    $ozellik4_baslik = $_POST['ozellik4_baslik'];
    $ozellik4_aciklama = $_POST['ozellik4_aciklama'];
    $aktif = isset($_POST['aktif']) ? 1 : 0;
    
    try {
        $sql = "UPDATE firma_bilgileri SET baslik = ?, icerik = ?, ozellik1_baslik = ?, ozellik1_aciklama = ?, ozellik2_baslik = ?, ozellik2_aciklama = ?, ozellik3_baslik = ?, ozellik3_aciklama = ?, ozellik4_baslik = ?, ozellik4_aciklama = ?, aktif = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $sonuc = $stmt->execute([$baslik, $icerik, $ozellik1_baslik, $ozellik1_aciklama, $ozellik2_baslik, $ozellik2_aciklama, $ozellik3_baslik, $ozellik3_aciklama, $ozellik4_baslik, $ozellik4_aciklama, $aktif, $id]);
        
        if ($sonuc) {
            $basari_mesaji = "Firma bilgileri başarıyla güncellendi!";
        } else {
            $hata_mesaji = "Güncelleme sırasında bir hata oluştu.";
        }
    } catch (PDOException $e) {
        $hata_mesaji = "Veritabanı hatası: " . $e->getMessage();
    }
}

// Firma bilgilerini listeleme
try {
    $stmt = $conn->query("SELECT * FROM firma_bilgileri ORDER BY id DESC");
    $firma_listesi = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Firma bilgileri listelenirken hata oluştu: " . $e->getMessage();
    $firma_listesi = [];
}

// Düzenleme modu için firma bilgisi getirme
$duzenle_modu = false;
$duzenle_firma = null;

if (isset($_GET['duzenle']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    try {
        $stmt = $conn->prepare("SELECT * FROM firma_bilgileri WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $duzenle_firma = $stmt->fetch();
        
        if ($duzenle_firma) {
            $duzenle_modu = true;
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
    <title>Admin Panel - Firma Bilgileri Yönetimi</title>
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
                <h1>Firma Bilgileri Yönetimi</h1>
                
            </div>
            
            <?php if (isset($basari_mesaji)): ?>
            <div class="alert success"><?php echo $basari_mesaji; ?></div>
            <?php endif; ?>
            
            <?php if (isset($hata_mesaji) && !empty($hata_mesaji)): ?>
            <div class="alert error"><?php echo $hata_mesaji; ?></div>
            <?php endif; ?>
            
            <div class="admin-box">
                <h2><?php echo $duzenle_modu ? 'Firma Bilgilerini Düzenle' : 'Firma Bilgilerini Ekle'; ?></h2>
                <form action="" method="post">
                    <?php if ($duzenle_modu): ?>
                    <input type="hidden" name="id" value="<?php echo $duzenle_firma['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="baslik">Başlık:</label>
                        <input type="text" id="baslik" name="baslik" value="<?php echo $duzenle_modu ? $duzenle_firma['baslik'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="icerik">İçerik:</label>
                        <textarea id="icerik" name="icerik" rows="8" required><?php echo $duzenle_modu ? $duzenle_firma['icerik'] : ''; ?></textarea>
                        <small>Firmanız hakkında bilgi yazabilirsiniz. HTML etiketleri kullanabilirsiniz.</small>
                    </div>
                    
                    <h3>Firma Özellikleri</h3>
                    
                    <div class="form-group">
                        <label for="ozellik1_baslik">Özellik 1 - Başlık:</label>
                        <input type="text" id="ozellik1_baslik" name="ozellik1_baslik" value="<?php echo $duzenle_modu ? $duzenle_firma['ozellik1_baslik'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ozellik1_aciklama">Özellik 1 - Açıklama:</label>
                        <input type="text" id="ozellik1_aciklama" name="ozellik1_aciklama" value="<?php echo $duzenle_modu ? $duzenle_firma['ozellik1_aciklama'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ozellik2_baslik">Özellik 2 - Başlık:</label>
                        <input type="text" id="ozellik2_baslik" name="ozellik2_baslik" value="<?php echo $duzenle_modu ? $duzenle_firma['ozellik2_baslik'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ozellik2_aciklama">Özellik 2 - Açıklama:</label>
                        <input type="text" id="ozellik2_aciklama" name="ozellik2_aciklama" value="<?php echo $duzenle_modu ? $duzenle_firma['ozellik2_aciklama'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ozellik3_baslik">Özellik 3 - Başlık:</label>
                        <input type="text" id="ozellik3_baslik" name="ozellik3_baslik" value="<?php echo $duzenle_modu ? $duzenle_firma['ozellik3_baslik'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ozellik3_aciklama">Özellik 3 - Açıklama:</label>
                        <input type="text" id="ozellik3_aciklama" name="ozellik3_aciklama" value="<?php echo $duzenle_modu ? $duzenle_firma['ozellik3_aciklama'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ozellik4_baslik">Özellik 4 - Başlık:</label>
                        <input type="text" id="ozellik4_baslik" name="ozellik4_baslik" value="<?php echo $duzenle_modu ? $duzenle_firma['ozellik4_baslik'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ozellik4_aciklama">Özellik 4 - Açıklama:</label>
                        <input type="text" id="ozellik4_aciklama" name="ozellik4_aciklama" value="<?php echo $duzenle_modu ? $duzenle_firma['ozellik4_aciklama'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group checkbox">
                        <input type="checkbox" id="aktif" name="aktif" <?php echo $duzenle_modu && $duzenle_firma['aktif'] == 1 ? 'checked' : (!$duzenle_modu ? 'checked' : ''); ?>>
                        <label for="aktif">Aktif</label>
                    </div>
                    
                    <div class="form-buttons">
                        <?php if ($duzenle_modu): ?>
                        <button type="submit" name="firma_guncelle" class="btn btn-primary">
                            <i class="fas fa-save"></i> Güncelle
                        </button>
                        <a href="admin-firma-bilgileri.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                        <?php else: ?>
                        <button type="submit" name="firma_ekle" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Bilgileri Ekle
                        </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            
            <div class="admin-box">
                <h2>Mevcut Firma Bilgileri</h2>
                <?php if (count($firma_listesi) > 0): ?>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Başlık</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($firma_listesi as $firma): ?>
                            <tr>
                                <td><?php echo $firma['id']; ?></td>
                                <td><?php echo $firma['baslik']; ?></td>
                                <td>
                                    <?php if ($firma['aktif'] == 1): ?>
                                        <span class="status active">Aktif</span>
                                    <?php else: ?>
                                        <span class="status inactive">Pasif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="admin-firma-bilgileri.php?duzenle=1&id=<?php echo $firma['id']; ?>" 
                                       class="btn btn-sm btn-edit">
                                        <i class="fas fa-edit"></i> Düzenle
                                    </a>
                                    <a href="admin-firma-bilgileri.php?sil=1&id=<?php echo $firma['id']; ?>" 
                                       class="btn btn-sm btn-delete" 
                                       onclick="return confirm('Bu firma bilgisini silmek istediğinize emin misiniz?')">
                                        <i class="fas fa-trash-alt"></i> Sil
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="no-data">
                    <p>Henüz firma bilgisi eklenmemiş.</p>
                </div>
                <?php endif; ?>
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