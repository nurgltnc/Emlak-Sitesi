<?php
session_start(); // Oturumu başlatıyoruz
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmlakNet</title>
    <link rel="stylesheet" href="stil.css"> <!-- CSS dosyasını ekliyoruz -->
</head>
<body>

    <header>
        <div class="navbar">
            <div class="logo">
                <a href="#">EmlakNet</a>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Satılık</a></li>
                    <li><a href="#">Kiralık</a></li>
                    <li><a href="#">Projeler</a></li>
                    <li><a href="#">Emlak Ofisleri</a></li>

                    <?php if (isset($_SESSION['username'])): ?>
                        <!-- Kullanıcı giriş yaptıysa -->
                        <li><span class="username"><?php echo $_SESSION['username']; ?></span></li>
                        <li><a href="cikis.php">Çıkış Yap</a></li>
                    <?php else: ?>
                        <!-- Kullanıcı giriş yapmadıysa -->
                        <li><a href="giris.php">Giriş Yap</a></li>
                        <li><a href="kayit.php">Kayıt Ol</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
