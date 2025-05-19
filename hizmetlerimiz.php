<?php require_once 'config/config.php'; ?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endüstriyel Otomasyon | Elektrik ve Zayıf Akım Sistemleri</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- Üst Bilgi Kısmı -->
    <!-- Üst Bilgi Kısmı -->
<div class="top-info">
    <div class="container">
        <?php 
        $site_ayarlari = getSiteSettings();
        if ($site_ayarlari) {
            $mesaj = $site_ayarlari['ust_mesaj'];
            $mesaj = str_replace('{telefon1}', '<a href="tel:'.$site_ayarlari['telefon1'].'">'.$site_ayarlari['telefon1'].'</a>', $mesaj);
            $mesaj = str_replace('{telefon2}', '<a href="tel:'.$site_ayarlari['telefon2'].'">'.$site_ayarlari['telefon2'].'</a>', $mesaj);
            echo '<p>'.$mesaj.'</p>';
        } else {
            echo '<p>İletişim bilgileri yüklenemiyor</p>';
        }
        ?>
    </div>
</div>
    <!-- Sabit İletişim İkonları -->
    <div class="contact-icons">
        <!-- Telefon İkonu - Sol Taraf -->
        <a href="tel:+905XXXXXXXXX" class="contact-icon phone-icon">
            <div class="icon-container">
                <i class="fas fa-phone-alt"></i>
            </div>
            <span class="icon-tooltip">Hemen Arayın!</span>
        </a>

        <!-- WhatsApp İkonu - Sağ Taraf -->
        <a href="https://wa.me/905XXXXXXXXX" target="_blank" class="contact-icon whatsapp-icon">
            <div class="icon-container">
                <i class="fab fa-whatsapp"></i>
            </div>
            <span class="icon-tooltip">Whatsapp'tan Bize Ulaşın!</span>
        </a>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <img src="images/logo2.png" alt="Şirket Logo">
                </a>
            </div>

            <div class="menu-icon">
                <i class="fas fa-bars"></i>
            </div>

            <ul class="nav-links">
                <li><a href="index.php">Anasayfa</a></li>
                <li><a href="hizmetlerimiz.php">Hizmetlerimiz</a></li>
                <li><a href="hakkimizda.php">Hakkımızda</a></li>
                <li><a href="iletisim.php">İletişim</a></li>
            </ul>
        </div>
    </nav>

    <!-- Ana İçerik Buraya Gelecek -->
    <main>
       
        <section class="services-section">
    <div class="container">
        <div class="section-header">
            <h2>Hizmetlerimiz</h2>
            <div class="section-line"></div>
        </div>
        
        <div class="services-grid">
            <?php
            $hizmetler = getHizmetler();
            if ($hizmetler && count($hizmetler) > 0) {
                foreach ($hizmetler as $hizmet) {
                    if ($hizmet['aktif'] == 1) {
                        echo '<div class="service-card">';
                        echo '<div class="service-image">';
                        echo '<img src="images/'.$hizmet['gorsel'].'" alt="'.$hizmet['baslik'].'">';
                        echo '</div>';
                        echo '<div class="service-content">';
                        echo '<h3>'.$hizmet['baslik'].'</h3>';
                        echo '<div class="service-text">'.$hizmet['icerik'].'</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
            } else {
                echo '<div class="service-empty">Henüz hizmet eklenmemiş.</div>';
            }
            ?>
        </div>
    </div>
</section>



<section class="service-areas-section">
    <div class="container">
        <?php
        $hizmet_bolgesi = getHizmetBolgeleri();
        if ($hizmet_bolgesi):
        ?>
        <div class="service-areas-content">
            <div class="service-areas-map">
                <!-- Google Maps iframe kodu -->
                <?php echo $hizmet_bolgesi['harita_kodu']; ?>
            </div>
            <div class="service-areas-text">
                <h2><?php echo $hizmet_bolgesi['baslik']; ?></h2>
                <div class="section-line"></div>
                <?php echo $hizmet_bolgesi['icerik']; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>


<section class="info-blocks-section">
    <div class="container">
        <?php
        $bilgi_bloklari = getBilgiBloklar();
        if ($bilgi_bloklari && count($bilgi_bloklari) > 0):
        ?>
        <div class="info-blocks">
            <?php foreach ($bilgi_bloklari as $blok): ?>
            <div class="info-block">
                <h2 class="info-block-title"><?php echo $blok['baslik']; ?></h2>
                <div class="info-block-content">
                    <?php echo $blok['icerik']; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

    </main>

<!-- Footer Kısmı -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-info">
                <?php
                $firma_sahipleri = getFirmaSahipleri();
                $footer_ayarlari = getFooterSettings();
                if ($firma_sahipleri && count($firma_sahipleri) > 0):
                ?>
                <h3>Firma Yönetimi</h3>
                <ul class="footer-contacts">
                    <?php foreach ($firma_sahipleri as $sahip): ?>
                    <li>
                        <span class="contact-name"><?php echo $sahip['isim']; ?><?php echo !empty($sahip['unvan']) ? ' - ' . $sahip['unvan'] : ''; ?></span>
                        <a href="tel:<?php echo $sahip['telefon']; ?>" class="contact-phone">
                            <i class="fas fa-phone-alt"></i> <?php echo $sahip['telefon']; ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                
                <?php if ($footer_ayarlari && !empty($footer_ayarlari['adres'])): ?>
                <div class="footer-address">
                    <h3>Adres</h3>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo $footer_ayarlari['adres']; ?></p>
                </div>
                <?php endif; ?>
                
                <?php if ($footer_ayarlari && !empty($footer_ayarlari['email'])): ?>
                <div class="footer-email">
                    <h3>E-posta</h3>
                    <p><a href="mailto:<?php echo $footer_ayarlari['email']; ?>"><i class="fas fa-envelope"></i> <?php echo $footer_ayarlari['email']; ?></a></p>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="footer-map">
                <?php if ($footer_ayarlari && !empty($footer_ayarlari['footer_harita'])): ?>
                <div class="map-container">
                    <?php echo $footer_ayarlari['footer_harita']; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> - Tüm Hakları Saklıdır.</p>
        </div>
    </div>
</footer>

    <script src="script/script.js"></script>
</body>

</html>