<?php
// Veritabanı bağlantısı
$host = "localhost";
$username = "root";
$password = "12345678";
$database = "emlak";
$conn = new mysqli($host, $username, $password, $database);

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// Kullanıcı oturum kontrolü
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Lütfen giriş yapın!";
    exit;
}

// Giriş yapan kullanıcının ID'sini al
$user_id = $_SESSION['user_id'];

// Kullanıcının favori ilanlarını veritabanından çek
$query_favoriler = "
    SELECT ilanlar.id, ilanlar.baslik, ilanlar.kategori, ilanlar.konum, ilanlar.fiyat, ilanlar.aciklama, ilanlar.tur, ilanlar.resim 
    FROM ilanlar
    INNER JOIN favoriler ON ilanlar.id = favoriler.ilan_id
    WHERE favoriler.user_id = ?
";

$stmt = $conn->prepare($query_favoriler);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$favoriler = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorilerim - EmlakNet</title>
	<link rel="icon" type="image/jpeg" href="images/ev.jpeg">
    <link rel="stylesheet" href="stil.css">
</head>
<body class="favoriler_ekran">

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
                <span>Hoşgeldiniz, <?php echo htmlspecialchars($_SESSION['username']); ?></span><br>
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

<!-- Favoriler -->
<main class="main-content">
    <section class="results-section">
        <div class="container">
            <?php if (!empty($favoriler)) { ?>
					<div class="icerik">
					<h2>Favori İlanlarım</h2>
					<ul class="ilan-listesi">
                    <?php foreach ($favoriler as $favori) { ?>
                        <li class="ilan-karti">
                            <img src="<?= htmlspecialchars($favori['resim']) ?>" alt="İlan Resmi" class="ilan-resmi">
                            <h3><?= htmlspecialchars($favori['baslik']) ?></h3>
                            <p>Fiyat: <?= htmlspecialchars($favori['fiyat']) ?> TL</p>
                            <p>Konum: <?= htmlspecialchars($favori['konum']) ?></p>
                            <div class="ilan-buttons">
                                <form method="post" action="ilan_basvur.php">
                                    <input type="hidden" name="ilan_id" value="<?= htmlspecialchars($favori['id']) ?>">
                                    <button type="submit" class="btn btn-basvur">İlana Başvur</button>
									
                                </form>
                                <form method="post" action="favori_sil.php">
                                    <input type="hidden" name="ilan_id" value="<?= htmlspecialchars($favori['id']) ?>">
                                    <button type="submit" class="btn btn-sil">&#128148;</button>
                                </form>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
				</div>
            <?php } else { ?>
                <p>Henüz favorilere eklenmiş bir ilan bulunmamaktadır.</p>
            <?php } ?>
        </div>
    </section>
</main>

<script src="sayfa.js"></script>
</body>
</html>
