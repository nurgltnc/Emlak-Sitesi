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
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Lütfen giriş yapın!";
    exit;
}
// Arama işlemi
$ilanlar = [];
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['query'])) {
    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
    $type = isset($_GET['type']) ? $_GET['type'] : '';

    $sql = "SELECT * FROM ilanlar WHERE (kategori = ? OR ? = '') 
            AND (tur = ? OR ? = '') 
            AND (konum LIKE ? OR baslik LIKE ?)";
    
    $stmt = $conn->prepare($sql);
    $searchQuery = '%' . $query . '%';
    $stmt->bind_param('ssssss', $kategori, $kategori, $type, $type, $searchQuery, $searchQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $ilanlar[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmlakNet - Arama Sonuçları</title>
	<link rel="icon" type="image/jpeg" href="images/ev.jpeg">
    <link rel="stylesheet" href="stil.css"> <!-- Harici CSS dosyasını ekliyoruz -->
</head>
<body class="arama_ekran">
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

    <!-- Arama Sonuçları -->
    <main class="main-content">
        <section class="results-section">
            <div class="container">
                <?php if (!empty($ilanlar)) { ?>
                    <div class="icerik">
					<h2>Arama Sonuçları:</h2>
					<ul class="ilan-listesi">
					
                        <?php foreach ($ilanlar as $ilan) { ?>
                            <li class="ilan-karti">
                                <img src="<?= htmlspecialchars($ilan['resim']) ?>" alt="İlan Resmi" class="ilan-resmi">
                                <h3><?= htmlspecialchars($ilan['baslik']) ?></h3>
                                <p>Fiyat: <?= htmlspecialchars($ilan['fiyat']) ?> TL</p>
                                <p>Konum: <?= htmlspecialchars($ilan['konum']) ?></p>
                                <!-- Detay sayfasına yönlendiren buton -->
                                <a href="detay.php?ilan_id=<?= $ilan['id'] ?>" class="btn detay-btn">
                                    Detaylara Git
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
					</div>
                <?php } else { ?>
                    <h2>Hiçbir sonuç bulunamadı.</h2>
                <?php } ?>
            </div>
        </section>
    </main>
 <script src="sayfa.js"></script>
</body>
</html>
