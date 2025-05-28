<?php
session_start();
include 'db.php'; // Veritabanı bağlantısını buraya ekle

// Veritabanından satılık ilanları çek
$sql = "SELECT * FROM ilanlar WHERE tur = 'proje'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmlakNet - Proje İlanlar</title>
	<link rel="icon" type="image/jpeg" href="images/ev.jpeg">
    <link rel="stylesheet" href="stil.css"> <!-- Harici CSS dosyasını ekliyoruz -->
</head>
<body class="aramaekran">
    <!-- Üst Menü -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>
                    <a href="anasayfa.php">
                        <img src="images/ev.jpeg" alt="EmlakNet Logo" style="width: 40px; height: 40px; border-radius: 50%; vertical-align: middle;">
                    </a>
                    EmlakNet
                </h1>
            </div>
            <nav>
                <ul>
                    <li><a href="satilik.php">Satılık</a></li>
                    <li><a href="kiralik.php">Kiralık</a></li>
                    <li><a href="projeler.php">Projeler</a></li>
                </ul>
            </nav>
            <div class="auth">
                <?php if (isset($_SESSION['username'])) { ?>
                    <span>Hoşgeldiniz, <?php echo $_SESSION['username']; ?></span><br>
                    <span class="menu-btn" onclick="openNav()">&#9776;</span>
                <?php } else { ?>
                    <button id="loginBtn" class="btn" type="button">Giriş Yap</button>
                    <button id="registerBtn" class="btn" type="button">Kayıt Ol</button>
                <?php } ?>
            </div>
        </div>
    </header>

    <!-- Açılır Menü -->
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="favoriler.php">Favori İlanlar</a>
        <a href="ilan_ekle.php">İlan Ekle</a>
        <a href="profile.php">Profilim</a>
        <a href="cikis.php">Çıkış Yap</a>
    </div>
    <div id="overlay" class="overlay" onclick="closeNav()"></div>

    <!-- Satılık İlanlar -->
    <main class="main-content">
        <section class="results-section">
            <div class="icerik">
                <h2>Projeler</h2>
                <?php if ($result->num_rows > 0) { ?>
                    <ul class="ilan-listesi">
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <li class="ilan-karti">
                                <img src="<?= htmlspecialchars($row['resim']) ?>" alt="İlan Resmi" class="ilan-resmi">
                                <h3><?= htmlspecialchars($row['baslik']) ?></h3>
                                <p>Fiyat: <?= htmlspecialchars($row['fiyat']) ?> TL</p>
                                <p>Konum: <?= htmlspecialchars($row['konum']) ?></p>
                                <a href="detay.php?ilan_id=<?= $row['id'] ?>" class="btn detay-btn">Detayları Gör</a>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } else { ?>
                    <p>Bu kategori için henüz ilan eklenmemiş.</p>
                <?php } ?>
            </div>
        </section>
    </main>
   <script src="sayfa.js"></script>
</body>
</html>
