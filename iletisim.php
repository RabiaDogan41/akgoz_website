<?php require_once 'config/config.php'; ?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim | Endüstriyel Otomasyon ve Elektrik Sistemleri</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
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
        <a href="tel:<?php echo isset($site_ayarlari['telefon1']) ? $site_ayarlari['telefon1'] : '+905XXXXXXXXX'; ?>" class="contact-icon phone-icon">
            <div class="icon-container">
                <i class="fas fa-phone-alt"></i>
            </div>
            <span class="icon-tooltip">Hemen Arayın!</span>
        </a>

        <!-- WhatsApp İkonu - Sağ Taraf -->
        <a href="https://wa.me/<?php echo isset($site_ayarlari['telefon1']) ? str_replace(['+', ' ', '(', ')', '-'], '', $site_ayarlari['telefon1']) : '905XXXXXXXXX'; ?>" target="_blank" class="contact-icon whatsapp-icon">
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

    <!-- Ana İçerik -->
    <main>
        <section class="contact-section">
            <div class="container">
                <div class="section-header text-center">
                    <h2>İletişim Bilgilerimiz</h2>
                    <div class="section-line center"></div>
                </div>
                
                <?php
                $footer_ayarlari = getFooterSettings();
                $firma_sahipleri = getFirmaSahipleri();
                ?>
                
                <div class="contact-content">
                    <!-- Harita -->
                    <div class="contact-map">
                        <?php if ($footer_ayarlari && !empty($footer_ayarlari['footer_harita'])): ?>
                            <?php echo $footer_ayarlari['footer_harita']; ?>
                        <?php else: ?>
                            <div class="no-map">
                                <p>Harita bilgisi bulunamadı.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- İletişim Bilgileri -->
                    <div class="contact-details">
                        <div class="contact-info">
                            <h3>Adres</h3>
                            <p><i class="fas fa-map-marker-alt"></i> <?php echo $footer_ayarlari ? $footer_ayarlari['adres'] : 'Adres bilgisi bulunamadı.'; ?></p>
                        </div>
                        
                        <div class="contact-info">
                            <h3>E-posta</h3>
                            <p><a href="mailto:<?php echo $footer_ayarlari ? $footer_ayarlari['email'] : ''; ?>"><i class="fas fa-envelope"></i> <?php echo $footer_ayarlari ? $footer_ayarlari['email'] : 'E-posta bilgisi bulunamadı.'; ?></a></p>
                        </div>
                        
                        <?php if ($site_ayarlari): ?>
                        <div class="contact-info">
                            <h3>Telefon Numaraları</h3>
                            <p><a href="tel:<?php echo $site_ayarlari['telefon1']; ?>"><i class="fas fa-phone-alt"></i> <?php echo $site_ayarlari['telefon1']; ?></a></p>
                            <p><a href="tel:<?php echo $site_ayarlari['telefon2']; ?>"><i class="fas fa-phone-alt"></i> <?php echo $site_ayarlari['telefon2']; ?></a></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($firma_sahipleri && count($firma_sahipleri) > 0): ?>
                        <div class="contact-info">
                            <h3>Firma Yönetimi</h3>
                            <ul class="contact-people">
                                <?php foreach ($firma_sahipleri as $sahip): ?>
                                <li>
                                    <div class="person-name"><?php echo $sahip['isim']; ?> <?php echo !empty($sahip['unvan']) ? '<span class="person-title">(' . $sahip['unvan'] . ')</span>' : ''; ?></div>
                                    <a href="tel:<?php echo $sahip['telefon']; ?>" class="person-phone"><i class="fas fa-phone-alt"></i> <?php echo $sahip['telefon']; ?></a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Kısmı -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <?php
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