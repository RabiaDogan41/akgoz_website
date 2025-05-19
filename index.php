<?php require_once 'config/config.php'; ?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebze Kocaeli Endüstriyel Otomasyon | Elektrik ve Zayıf Akım Sistemleri Uzmanı</title>
    <meta name="description" content="Gebze ve Kocaeli'de endüstriyel otomasyon, elektrik-elektronik sistemleri, yangın ve hırsız alarm sistemleri, IP kamera, telefon santral kurulumu ve bakım hizmetleri sunan profesyonel firma.">
    <meta name="keywords" content="Gebze otomasyon, Kocaeli elektrik sistemleri, endüstriyel otomasyon Gebze, zayıf akım sistemleri Kocaeli, yangın alarm sistemleri, hırsız alarm, IP kamera sistemleri, fabrika bakım onarım, elektrikli araç şarj istasyonu, güneş enerji sistemleri">
    <meta name="robots" content="index, follow">
    <meta name="geo.region" content="TR-41">
    <meta name="geo.placename" content="Gebze, Kocaeli">
    <link rel="canonical" href="https://www.sitenizinadi.com/">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Firma Adınız",
  "image": "https://www.akgozendustriyel.com/images/logo2.png",
  "description": "Gebze ve Kocaeli'nin lider endüstriyel otomasyon ve zayıf akım sistemleri firması. Elektrik elektronik arıza onarım bakım, yangın ve hırsız alarm sistemleri, IP kamera ve telefon santral sistemleri hizmetleri.",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Arapçeşme Mahallesi Kavak Caddesi 1047/1 Sokak No:3 ",
    "addressLocality": "Gebze",
    "addressRegion": "Kocaeli",
    "postalCode": "41400",
    "addressCountry": "TR"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 40.7929888,
    "longitude": 29.3877658
  },
  "url": "https://www.akgozendustriyel.com",
  "telephone": "+905427829741",
  "priceRange": "₺₺",
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday"
      ],
      "opens": "08:30",
      "closes": "18:00"
    }
  ],
  "sameAs": [
    "https://www.facebook.com/akgozendustriyel",
    "https://www.instagram.com/akgozendustriyel"
  ],
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "name": "Hizmetlerimiz",
    "itemListElement": [
      {
        "@type": "OfferCatalog",
        "name": "Endüstriyel Otomasyon",
        "description": "Gebze ve Kocaeli'de endüstriyel otomasyon çözümleri"
      },
      {
        "@type": "OfferCatalog",
        "name": "Elektrik Elektronik Sistemleri",
        "description": "Gebze ve Kocaeli'de elektrik elektronik sistem kurulumu ve bakımı"
      },    
      {
        "@type": "OfferCatalog",
        "name": "Yangın Alarm Sistemleri",
        "description": "Gebze ve Kocaeli'de profesyonel yangın alarm sistemleri"
      },
      {
        "@type": "OfferCatalog",
        "name": "Hırsız Alarm Sistemleri",
        "description": "Gebze ve Kocaeli'de güvenlik ve hırsız alarm sistemleri"
      },
      {
        "@type": "OfferCatalog",
        "name": "IP Kamera Sistemleri",
        "description": "Gebze ve Kocaeli'de IP kamera ve güvenlik sistemleri"
      }
    ]
  }
}
</script>
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
        <a href="tel:+905427829741" class="contact-icon phone-icon">
            <div class="icon-container">
                <i class="fas fa-phone-alt"></i>
            </div>
            <span class="icon-tooltip">Hemen Arayın!</span>
        </a>

        <!-- WhatsApp İkonu - Sağ Taraf -->
        <a href="https://wa.me/905427829741" target="_blank" class="contact-icon whatsapp-icon">
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
                    <img src="images/logo2.png" alt="Gebze Kocaeli Akgöz Endüstriyel Otomasyon">
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
        <!-- Slider Section -->
        <section class="slider-section">
            
            <div class="slider-container">
                <!-- Slider İçeriği -->
                <div class="slide fade">
                    <img src="images/slider1.png" alt="Gebze Kocaeli Endüstriyel Otomasyon">
                    <div class="slide-text">
                        <h2>Endüstriyel Otomasyon Çözümleri</h2>
                        <p>Modern endüstri için profesyonel ve yenilikçi otomasyon sistemleri</p>
                        <a href="hizmetlerimiz.php" class="btn">Hizmetlerimiz</a>
                    </div>
                </div>

                <div class="slide fade">
                    <img src="images/slider2.png" alt="Gebze Kocaeli Elektrik Sistemleri">
                    <div class="slide-text">
                        <h2>Elektrik Sistemleri</h2>
                        <p>Güvenilir ve verimli elektrik altyapısı çözümleri</p>
                        <a href="hizmetlerimiz.php" class="btn">Detaylı Bilgi</a>
                    </div>
                </div>

                <div class="slide fade">
                    <img src="images/slider3.png" alt="Gebze Kocaeli Zayıf Akım Sistemleri">
                    <div class="slide-text">
                        <h2>Zayıf Akım Sistemleri</h2>
                        <p>Akıllı bina ve tesis çözümleri için modern teknolojiler</p>
                        <a href="iletisim.php" class="btn">Bizimle İletişime Geçin</a>
                    </div>
                </div>

                <!-- Slider Gezinme Düğmeleri -->
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>

            <!-- Slider Gösterge Noktaları -->
            <div class="dots-container">
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
            </div>
        </section>


        <!-- Özellik Kutuları -->
        <section class="features-section">
            <div class="container">
                <div class="features-grid">
                    <!-- Özellik 1 -->
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <h3>Kalite ve Güven</h3>
                        <p>Sektörde 20 yılı aşkın deneyimimizle, en yüksek kalite standartlarında hizmet sunarak müşterilerimizin güvenini kazanıyoruz.</p>
                    </div>

                    <!-- Özellik 2 -->
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Müşteri Memnuniyeti</h3>
                        <p>Her projede müşteri memnuniyetini önceliğimiz olarak görüyor, ihtiyaçlarınıza özel çözümler sunuyoruz.</p>
                    </div>

                    <!-- Özellik 3 -->
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h3>Teknolojik Uzmanlık</h3>
                        <p>Endüstriyel otomasyon alanında en son teknolojileri kullanarak, verimli ve akıllı sistem çözümleri sağlıyoruz.</p>
                    </div>

                    <!-- Özellik 4 -->
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>7/24 Teknik Destek</h3>
                        <p>Kurulum sonrası sürekli teknik destek ve bakım hizmetlerimizle sistemlerinizin kesintisiz çalışmasını garanti ediyoruz.</p>
                    </div>
                </div>
            </div>
        </section>

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

<section class="about-company-section">
    <div class="container">
        <?php
        $firma_bilgileri = getFirmaBilgileri();
        if ($firma_bilgileri):
        ?>
        <div class="about-company-content">
            <div class="section-header text-center">
                <h2><?php echo $firma_bilgileri['baslik']; ?></h2>
                <div class="section-line center"></div>
            </div>
            <div class="about-company-text">
                <?php echo $firma_bilgileri['icerik']; ?>
                
                <div class="company-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="feature-text">
                            <h4><?php echo $firma_bilgileri['ozellik1_baslik']; ?></h4>
                            <p><?php echo $firma_bilgileri['ozellik1_aciklama']; ?></p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="feature-text">
                            <h4><?php echo $firma_bilgileri['ozellik2_baslik']; ?></h4>
                            <p><?php echo $firma_bilgileri['ozellik2_aciklama']; ?></p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="feature-text">
                            <h4><?php echo $firma_bilgileri['ozellik3_baslik']; ?></h4>
                            <p><?php echo $firma_bilgileri['ozellik3_aciklama']; ?></p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="feature-text">
                            <h4><?php echo $firma_bilgileri['ozellik4_baslik']; ?></h4>
                            <p><?php echo $firma_bilgileri['ozellik4_aciklama']; ?></p>
                        </div>
                    </div>
                </div>
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


<section class="owners-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Firma Yönetimi</h2>
            <div class="section-line center"></div>
        </div>
        
        <?php
        $firma_sahipleri = getFirmaSahipleri();
        if ($firma_sahipleri && count($firma_sahipleri) > 0):
        ?>
        <div class="owners-grid">
            <?php foreach ($firma_sahipleri as $sahip): ?>
            <div class="owner-card">
                <div class="owner-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="owner-details">
                    <h3><?php echo $sahip['isim']; ?></h3>
                    <?php if (!empty($sahip['unvan'])): ?>
                    <div class="owner-title"><?php echo $sahip['unvan']; ?></div>
                    <?php endif; ?>
                    <a href="tel:<?php echo $sahip['telefon']; ?>" class="owner-phone">
                        <i class="fas fa-phone-alt"></i> <?php echo $sahip['telefon']; ?>
                    </a>
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
            <p>&copy; <?php echo date('Y'); ?> - Akgöz Endüstriyel Otomasyon-Elektrik Elektronik. Tüm Hakları Saklıdır.</p>
        </div>
    </div>
</footer>

    <script src="script/script.js"></script>
</body>

</html>