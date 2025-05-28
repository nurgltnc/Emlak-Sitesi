<?php
// Veritabanı bağlantısı
$host = "localhost";
$username = "root";
$password = "12345678";
$database = "emlak";
$conn = new mysqli($host, $username, $password, $database);

session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Lütfen giriş yapın!";
    exit;
}

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// İlan ID'sini URL'den al
if (isset($_GET['ilan_id']) && is_numeric($_GET['ilan_id'])) {
    $ilan_id = $_GET['ilan_id'];
    
    // İlanı veritabanından al
    $sql = "SELECT * FROM ilanlar WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $ilan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $ilan = $result->fetch_assoc();
    } else {
        echo "İlan bulunamadı.";
        exit;
    }
} else {
    echo "Geçersiz ilan ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İlan Detayı</title>
	<link rel="icon" type="image/jpeg" href="images/ev.jpeg">
    <link rel="stylesheet" href="stil.css"> <!-- Harici CSS dosyasını ekliyoruz -->
</head>
<body>
	<!-- Üst Menü -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>
				<a href="anasayfa.php">
                <img src="images/ev.jpeg" alt="EmlakNet Logo"  style="width: 40px; height: 40px; border-radius: 50%; vertical-align: middle;">
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
                    <span class="menu-btn" onclick="openNav()">&#9776;</span> <!-- Açılır Menü Butonu -->
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

    <!-- Arka Plan Kapanma Alanı -->
    <div id="overlay" class="overlay" onclick="closeNav()"></div>
<div class="ilanekle">
    <!-- İlan Detayları -->
    <main class="main-content">
        <section class="detay-section">
            <div class="detay-container">
                <h2>İlan Detayı</h2>
                <div class="detay-karti">
                    <img src="<?= htmlspecialchars($ilan['resim']) ?>" alt="İlan Resmi" class="detay-resmi">
                    <h3><?= htmlspecialchars($ilan['baslik']) ?></h3>
                    <p><strong>Fiyat:</strong> <?= htmlspecialchars($ilan['fiyat']) ?> TL</p>
                    <p><strong>Konum:</strong> <?= htmlspecialchars($ilan['konum']) ?></p>
                    <p><strong>Açıklama:</strong> <?= htmlspecialchars($ilan['aciklama']) ?></p>
                    <p><strong>Kategori:</strong> <?= htmlspecialchars($ilan['kategori']) ?></p>
                    <p><strong>Tür:</strong> <?= htmlspecialchars($ilan['tur']) ?></p>
                    
                    <div class="button-container">
					<a href="favori_ekle.php?ilan_id=<?= $ilan['id'] ?>" class="btn">❤️</a>
					<a href="ilan_basvur.php?ilan_id=<?= $ilan['id'] ?>" class="btn">İlana Başvur</a>
					</div>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
