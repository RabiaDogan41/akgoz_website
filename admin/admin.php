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

// Hizmet silme işlemi
if (isset($_GET['sil']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    try {
        // Önce görseli al
        $stmt = $conn->prepare("SELECT gorsel FROM hizmetler WHERE id = ?");
        $stmt->execute([$id]);
        $gorsel = $stmt->fetchColumn();
        
        // Veritabanından kaydı sil
        $stmt = $conn->prepare("DELETE FROM hizmetler WHERE id = ?");
        $sonuc = $stmt->execute([$id]);
        
        if ($sonuc) {
            // Görseli dosya sisteminden sil
            if (!empty($gorsel) && file_exists('../images/' . $gorsel)) {
                unlink('../images/' . $gorsel);
            }
            
            $basari_mesaji = 'Hizmet başarıyla silindi!';
        } else {
            $hata_mesaji = 'Silme işlemi başarısız oldu.';
        }
    } catch(PDOException $e) {
        $hata_mesaji = 'Veritabanı hatası: ' . $e->getMessage();
    }
    
    // Sayfa yenilensin diye yönlendirme yapalım
    header('Location: admin.php?silindi=1');
    exit;
}

// Silme işlemi tamamlandığında
if (isset($_GET['silindi']) && $_GET['silindi'] == '1') {
    $basari_mesaji = 'Hizmet başarıyla silindi!';
}

// Hizmet ekleme işlemi
if (isset($_POST['hizmet_ekle'])) {
    $baslik = $_POST['baslik'];
    $icerik = $_POST['icerik'];
    $aktif = isset($_POST['aktif']) ? 1 : 0;
    
    // Hata mesajını başlangıçta boş olarak ayarla
    $hata_mesaji = '';
    
    // Dosya yükleme işlemi
    if (isset($_FILES['gorsel']) && $_FILES['gorsel']['error'] === 0) {
        $gorsel = $_FILES['gorsel']['name']; // Doğrudan dosya adını kullanalım
        $tmp_file = $_FILES['gorsel']['tmp_name'];
        $hedef_yol = "../images/" . $gorsel;
        
        // Dosyayı taşıma
        if (move_uploaded_file($tmp_file, $hedef_yol)) {
            // Dosya başarıyla yüklendi
            
            // Veritabanına ekleme
            try {
                $sql = "INSERT INTO hizmetler (baslik, icerik, gorsel, aktif) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $sonuc = $stmt->execute([$baslik, $icerik, $gorsel, $aktif]);
                
                if ($sonuc) {
                    $basari_mesaji = "Hizmet başarıyla eklendi!";
                } else {
                    $hata_mesaji = "Veritabanına eklenirken bir hata oluştu.";
                }
            } catch (PDOException $e) {
                $hata_mesaji = "Veritabanı hatası: " . $e->getMessage();
            }
        } else {
            $hata_mesaji = "Dosya yüklenirken bir hata oluştu.";
        }
    } else {
        $hata_mesaji = "Lütfen bir görsel seçin.";
    }
}

// Hizmetleri listeleme
try {
    $stmt = $conn->query("SELECT * FROM hizmetler ORDER BY id DESC");
    $hizmetler = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Hizmetler listelenirken hata oluştu: " . $e->getMessage();
    $hizmetler = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Hizmet Yönetimi</title>
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
                <h1>Hizmet Yönetimi</h1>
                
            </div>
            
            <?php if (isset($basari_mesaji)): ?>
            <div class="alert success"><?php echo $basari_mesaji; ?></div>
            <?php endif; ?>
            
            <?php if (isset($hata_mesaji) && !empty($hata_mesaji)): ?>
            <div class="alert error"><?php echo $hata_mesaji; ?></div>
            <?php endif; ?>
            
            <div class="admin-box">
                <h2>Yeni Hizmet Ekle</h2>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="baslik">Hizmet Başlığı:</label>
                        <input type="text" id="baslik" name="baslik" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="icerik">Hizmet İçeriği:</label>
                        <textarea id="icerik" name="icerik" rows="5" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="gorsel">Hizmet Görseli:</label>
                        <input type="file" id="gorsel" name="gorsel" required>
                    </div>
                    
                    <div class="form-group checkbox">
                        <input type="checkbox" id="aktif" name="aktif" checked>
                        <label for="aktif">Aktif</label>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" name="hizmet_ekle" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Hizmet Ekle
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="admin-box">
                <h2>Mevcut Hizmetler</h2>
                <?php if (count($hizmetler) > 0): ?>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Görsel</th>
                                <th>Başlık</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hizmetler as $hizmet): ?>
                            <tr>
                                <td><?php echo $hizmet['id']; ?></td>
                                <td>
                                    <img src="../images/<?php echo $hizmet['gorsel']; ?>" 
                                         alt="<?php echo $hizmet['baslik']; ?>" 
                                         class="thumbnail">
                                </td>
                                <td><?php echo $hizmet['baslik']; ?></td>
                                <td>
                                    <?php if ($hizmet['aktif'] == 1): ?>
                                        <span class="status active">Aktif</span>
                                    <?php else: ?>
                                        <span class="status inactive">Pasif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="admin.php?sil=1&id=<?php echo $hizmet['id']; ?>" 
                                       class="btn btn-sm btn-delete" 
                                       onclick="return confirm('Bu hizmeti silmek istediğinize emin misiniz?')">
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
                    <p>Henüz hizmet eklenmemiş.</p>
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