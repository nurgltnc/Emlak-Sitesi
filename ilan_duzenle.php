<?php
session_start();

// Veritabanı bağlantısı
$host = "localhost";
$username = "root";
$password = "12345678";
$database = "emlak";

$dsn = "mysql:host=$host;dbname=$database;charset=utf8";
try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}

$ilan_id = isset($_GET['ilan_id']) ? intval($_GET['ilan_id']) : 0;

// İlanı veritabanından alıyoruz
$ilan_query = $db->prepare("SELECT * FROM ilanlar WHERE id = ? AND user_id = ?");
$ilan_query->execute([$ilan_id, $_SESSION['user_id']]);
$ilan = $ilan_query->fetch(PDO::FETCH_ASSOC);

// İlan yoksa veya kullanıcıya ait değilse hata mesajı ver
if (!$ilan) {
    die("Bu ilana erişim izniniz yok veya ilan bulunamadı.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen veriler
    $baslik = isset($_POST['baslik']) ? trim($_POST['baslik']) : null;
    $kategori = isset($_POST['kategori']) ? trim($_POST['kategori']) : null;
    $tur = isset($_POST['tur']) ? trim($_POST['tur']) : null;
    $konum = isset($_POST['konum']) ? trim($_POST['konum']) : null;
    $fiyat = isset($_POST['fiyat']) ? floatval($_POST['fiyat']) : null;
    $aciklama = isset($_POST['aciklama']) ? trim($_POST['aciklama']) : null;
    $resim = isset($_POST['resim']) ? trim($_POST['resim']) : null;

    // Verilerin kontrolü
    if ($baslik && $kategori && $tur && $konum && $fiyat && $aciklama) {
        // İlanı güncelleme sorgusu
        $update_query = $db->prepare("UPDATE ilanlar SET baslik = ?, kategori = ?, tur = ?, konum = ?, fiyat = ?, aciklama = ?, resim = ? WHERE id = ?");
        $update_query->execute([$baslik, $kategori, $tur, $konum, $fiyat, $aciklama, $resim, $ilan_id]);

        // Başarılı güncelleme sonrası yönlendirme
        header('Location: profile.php');
        exit();
    } else {
        echo "<p class='error-message'>Lütfen tüm alanları doldurun.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/jpeg" href="images/ev.jpeg">
    <link rel="stylesheet" href="stil.css">
    <title>İlan Düzenle</title>
</head>
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

<body>
 <div class="aramaekran">
    <div class="form-container">
        <h4>İlan Düzenle</h4>
        <form method="post">
            <div class="form-group">
                <label for="baslik">Başlık:</label>
                <input type="text" id="baslik" name="baslik" value="<?= htmlspecialchars($ilan['baslik']) ?>" required>
            </div>

            <div class="form-group">
                <label for="aciklama">Açıklama:</label>
                <textarea id="aciklama" name="aciklama" required><?= htmlspecialchars($ilan['aciklama']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="kategori">Kategori:</label>
                <select id="kategori" name="kategori" required>
                    <option value="satilik" <?= $ilan['kategori'] == 'satilik' ? 'selected' : '' ?>>Satılık</option>
                    <option value="kiralik" <?= $ilan['kategori'] == 'kiralik' ? 'selected' : '' ?>>Kiralık</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tur">Tür:</label>
                <select id="tur" name="tur" required>
                    <option value="konut" <?= $ilan['tur'] == 'konut' ? 'selected' : '' ?>>Konut</option>
                    <option value="arsa" <?= $ilan['tur'] == 'arsa' ? 'selected' : '' ?>>Arsa</option>
                    <option value="ofis" <?= $ilan['tur'] == 'ofis' ? 'selected' : '' ?>>İşyeri</option>
                    <option value="dukkan" <?= $ilan['tur'] == 'dukkan' ? 'selected' : '' ?>>Dükkan</option>
                    <option value="kafe" <?= $ilan['tur'] == 'kafe' ? 'selected' : '' ?>>Kafe</option>
                    <option value="restoran" <?= $ilan['tur'] == 'restoran' ? 'selected' : '' ?>>Restoran</option>
                </select>
            </div>

            <div class="form-group">
                <label for="konum">Konum:</label>
                <input type="text" id="konum" name="konum" value="<?= htmlspecialchars($ilan['konum']) ?>" required>
            </div>

            <div class="form-group">
                <label for="fiyat">Fiyat (TL):</label>
                <input type="number" id="fiyat" name="fiyat" step="0.01" value="<?= htmlspecialchars($ilan['fiyat']) ?>" required>
            </div>

            <div class="form-group">
                <label for="resim">Resim URL:</label>
                <input type="text" id="resim" name="resim" value="<?= htmlspecialchars($ilan['resim']) ?>">
            </div>

            <button type="submit">Kaydet</button>
        </form>
    </div>
</div>
	  <script src="sayfa.js"></script>
</body>
</html>
