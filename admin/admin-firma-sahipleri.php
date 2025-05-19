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


// Firma sahibi ekleme işlemi
if (isset($_POST['sahip_ekle'])) {
    $isim = $_POST['isim'];
    $telefon = $_POST['telefon'];
    $unvan = $_POST['unvan'];
    $sira = isset($_POST['sira']) ? intval($_POST['sira']) : 0;
    $aktif = isset($_POST['aktif']) ? 1 : 0;
    
    // Veritabanına ekleme
    try {
        $sql = "INSERT INTO firma_sahipleri (isim, telefon, unvan, sira, aktif) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $sonuc = $stmt->execute([$isim, $telefon, $unvan, $sira, $aktif]);
        
        if ($sonuc) {
            $basari_mesaji = "Firma sahibi başarıyla eklendi!";
        } else {
            $hata_mesaji = "Veritabanına eklenirken bir hata oluştu.";
        }
    } catch (PDOException $e) {
        $hata_mesaji = "Veritabanı hatası: " . $e->getMessage();
    }
}

// Firma sahibi silme işlemi
if (isset($_GET['sil']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    try {
        // Veritabanından kaydı sil
        $stmt = $conn->prepare("DELETE FROM firma_sahipleri WHERE id = ?");
        $sonuc = $stmt->execute([$id]);
        
        if ($sonuc) {
            $basari_mesaji = 'Firma sahibi başarıyla silindi!';
        } else {
            $hata_mesaji = 'Silme işlemi başarısız oldu.';
        }
    } catch(PDOException $e) {
        $hata_mesaji = 'Veritabanı hatası: ' . $e->getMessage();
    }
    
    // Sayfa yenilensin diye yönlendirme yapalım
    header('Location: admin-firma-sahipleri.php?silindi=1');
    exit;
}

// Silme işlemi tamamlandığında
if (isset($_GET['silindi']) && $_GET['silindi'] == '1') {
    $basari_mesaji = 'Firma sahibi başarıyla silindi!';
}

// Firma sahibi güncelleme işlemi
if (isset($_POST['sahip_guncelle']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $isim = $_POST['isim'];
    $telefon = $_POST['telefon'];
    $unvan = $_POST['unvan'];
    $sira = isset($_POST['sira']) ? intval($_POST['sira']) : 0;
    $aktif = isset($_POST['aktif']) ? 1 : 0;
    
    try {
        $sql = "UPDATE firma_sahipleri SET isim = ?, telefon = ?, unvan = ?, sira = ?, aktif = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $sonuc = $stmt->execute([$isim, $telefon, $unvan, $sira, $aktif, $id]);
        
        if ($sonuc) {
            $basari_mesaji = "Firma sahibi başarıyla güncellendi!";
        } else {
            $hata_mesaji = "Güncelleme sırasında bir hata oluştu.";
        }
    } catch (PDOException $e) {
        $hata_mesaji = "Veritabanı hatası: " . $e->getMessage();
    }
}

// Firma sahiplerini listeleme
try {
    $stmt = $conn->query("SELECT * FROM firma_sahipleri ORDER BY sira ASC");
    $sahip_listesi = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Firma sahipleri listelenirken hata oluştu: " . $e->getMessage();
    $sahip_listesi = [];
}

// Düzenleme modu için firma sahibi getirme
$duzenle_modu = false;
$duzenle_sahip = null;

if (isset($_GET['duzenle']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    try {
        $stmt = $conn->prepare("SELECT * FROM firma_sahipleri WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $duzenle_sahip = $stmt->fetch();
        
        if ($duzenle_sahip) {
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
    <title>Admin Panel - Firma Sahipleri Yönetimi</title>
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
                <h1>Firma Sahipleri Yönetimi</h1>
               
            </div>
            
            <?php if (isset($basari_mesaji)): ?>
            <div class="alert success"><?php echo $basari_mesaji; ?></div>
            <?php endif; ?>
            
            <?php if (isset($hata_mesaji) && !empty($hata_mesaji)): ?>
            <div class="alert error"><?php echo $hata_mesaji; ?></div>
            <?php endif; ?>
            
            <div class="admin-box">
                <h2><?php echo $duzenle_modu ? 'Firma Sahibini Düzenle' : 'Yeni Firma Sahibi Ekle'; ?></h2>
                <form action="" method="post">
                    <?php if ($duzenle_modu): ?>
                    <input type="hidden" name="id" value="<?php echo $duzenle_sahip['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="isim">İsim:</label>
                        <input type="text" id="isim" name="isim" value="<?php echo $duzenle_modu ? $duzenle_sahip['isim'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefon">Telefon:</label>
                        <input type="text" id="telefon" name="telefon" value="<?php echo $duzenle_modu ? $duzenle_sahip['telefon'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="unvan">Ünvan:</label>
                        <input type="text" id="unvan" name="unvan" value="<?php echo $duzenle_modu ? $duzenle_sahip['unvan'] : ''; ?>">
                        <small>Örnek: Genel Müdür, Teknik Müdür vb.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="sira">Sıralama:</label>
                        <input type="number" id="sira" name="sira" value="<?php echo $duzenle_modu ? $duzenle_sahip['sira'] : '0'; ?>" min="0">
                    </div>
                    
                    <div class="form-group checkbox">
                        <input type="checkbox" id="aktif" name="aktif" <?php echo $duzenle_modu && $duzenle_sahip['aktif'] == 1 ? 'checked' : (!$duzenle_modu ? 'checked' : ''); ?>>
                        <label for="aktif">Aktif</label>
                    </div>
                    
                    <div class="form-buttons">
                        <?php if ($duzenle_modu): ?>
                        <button type="submit" name="sahip_guncelle" class="btn btn-primary">
                            <i class="fas fa-save"></i> Güncelle
                        </button>
                        <a href="admin-firma-sahipleri.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                        <?php else: ?>
                        <button type="submit" name="sahip_ekle" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ekle
                        </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            
            <div class="admin-box">
                <h2>Mevcut Firma Sahipleri</h2>
                <?php if (count($sahip_listesi) > 0): ?>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>İsim</th>
                                <th>Telefon</th>
                                <th>Ünvan</th>
                                <th>Sıra</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sahip_listesi as $sahip): ?>
                            <tr>
                                <td><?php echo $sahip['id']; ?></td>
                                <td><?php echo $sahip['isim']; ?></td>
                                <td><?php echo $sahip['telefon']; ?></td>
                                <td><?php echo $sahip['unvan']; ?></td>
                                <td><?php echo $sahip['sira']; ?></td>
                                <td>
                                    <?php if ($sahip['aktif'] == 1): ?>
                                        <span class="status active">Aktif</span>
                                    <?php else: ?>
                                        <span class="status inactive">Pasif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="admin-firma-sahipleri.php?duzenle=1&id=<?php echo $sahip['id']; ?>" 
                                       class="btn btn-sm btn-edit">
                                        <i class="fas fa-edit"></i> Düzenle
                                    </a>
                                    <a href="admin-firma-sahipleri.php?sil=1&id=<?php echo $sahip['id']; ?>" 
                                       class="btn btn-sm btn-delete" 
                                       onclick="return confirm('Bu firma sahibini silmek istediğinize emin misiniz?')">
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
                    <p>Henüz firma sahibi eklenmemiş.</p>
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