<?php
// Hata raporlamayı açın
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Oturum başlat
session_start();

// Yönlendirme URL'si için parametre kontrolü
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'admin.php';

// Zaten giriş yapılmışsa, yönlendirme yapılacak sayfaya git
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // Eğer yönlendirme admin-login.php'ye ise, admin.php'ye git
    if (strpos($redirect, 'admin-login.php') !== false) {
        header('Location: admin.php');
    } else {
        // Aksi halde istenilen sayfaya git
        header('Location: ' . $redirect);
    }
    exit;
}

// Config dosyasını dahil et
require_once '../config/config.php';

$hata_mesaji = '';

// Giriş formu gönderilmişse
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kullanici_adi = isset($_POST['kullanici_adi']) ? trim($_POST['kullanici_adi']) : '';
    $parola = isset($_POST['parola']) ? $_POST['parola'] : '';
    $remember_redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'admin.php';
    
    // Veritabanında kullanıcıyı ara
    try {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE kullanici_adi = ? LIMIT 1");
        $stmt->execute([$kullanici_adi]);
        $kullanici = $stmt->fetch();
        
        // Kullanıcı bulundu ve şifre doğruysa
        if ($kullanici && password_verify($parola, $kullanici['sifre'])) {
            // Oturum bilgilerini ayarla
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $kullanici['id'];
            $_SESSION['admin_username'] = $kullanici['kullanici_adi'];
            
            // İstenilen sayfaya yönlendir
            if (strpos($remember_redirect, 'admin-login.php') !== false) {
                header('Location: admin.php');
            } else {
                header('Location: ' . $remember_redirect);
            }
            exit;
        } else {
            $hata_mesaji = 'Geçersiz kullanıcı adı veya şifre!';
        }
    } catch (PDOException $e) {
        $hata_mesaji = 'Veritabanı hatası: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Giriş</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        
        .login-form .form-group {
            margin-bottom: 20px;
        }
        
        .login-form label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        
        .login-form .input-group {
            position: relative;
        }
        
        .login-form .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #888;
        }
        
        .login-form input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .login-form input:focus {
            border-color: #b10000;
            outline: none;
        }
        
        .login-button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #b10000;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .login-button:hover {
            background-color: #ff0000;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .back-link a:hover {
            color: #b10000;
        }
        
        @media screen and (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Admin Paneli Girişi</h1>
            <p>Lütfen giriş bilgilerinizi giriniz</p>
        </div>
        
        <?php if (!empty($hata_mesaji)): ?>
        <div class="error-message">
            <?php echo $hata_mesaji; ?>
        </div>
        <?php endif; ?>
        
        <form class="login-form" action="" method="post">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
            
            <div class="form-group">
                <label for="kullanici_adi">Kullanıcı Adı</label>
                <div class="input-group">
                    <i class="input-icon fas fa-user"></i>
                    <input type="text" id="kullanici_adi" name="kullanici_adi" required autofocus>
                </div>
            </div>
            
            <div class="form-group">
                <label for="parola">Şifre</label>
                <div class="input-group">
                    <i class="input-icon fas fa-lock"></i>
                    <input type="password" id="parola" name="parola" required>
                </div>
            </div>
            
            <button type="submit" class="login-button">
                <i class="fas fa-sign-in-alt"></i> Giriş Yap
            </button>
        </form>
        
        <div class="back-link">
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Siteye Dön</a>
        </div>
    </div>
</body>
</html>