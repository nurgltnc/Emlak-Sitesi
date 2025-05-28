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
$user_id = $_SESSION['user_id'];

// Kullanıcı bilgilerini çek
$user_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user = $user_query->get_result()->fetch_assoc();

// Kullanıcının ilanlarını çek
$ilanlar_query = $conn->prepare("SELECT * FROM ilanlar WHERE user_id = ?");
$ilanlar_query->bind_param("i", $user_id);
$ilanlar_query->execute();
$ilanlar = $ilanlar_query->get_result()->fetch_all(MYSQLI_ASSOC);

// Kullanıcının başvurduğu ilanları çek
$basvurular_query = $conn->prepare("
    SELECT b.*, i.baslik, i.kategori, i.tur, i.konum, i.fiyat, i.aciklama, i.resim
    FROM basvurular b 
    INNER JOIN ilanlar i ON b.ilan_id = i.id 
    WHERE b.user_id = ?
");

$basvurular_query->bind_param("i", $user_id);
$basvurular_query->execute();
$basvurular = $basvurular_query->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/jpeg" href="images/ev.jpeg">
    <link rel="stylesheet" href="stil.css?v=2.0">
    <title>Profil</title>
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

    <!-- Profil Bölümü -->
    <div class="ilanekle">
        <section class="profile-section">
            <h4>Profil</h4>
            <div class="profile-info">
                <p><strong>Ad:</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
				<p><strong>Telefon:</strong> <?= htmlspecialchars($user['cep_tel']) ?></p>
                <a href="kullanici_duzenle.php" class="buton">Bilgileri Düzenle</a>
            </div>
        </section>
	<div class="container">
        <!-- User's Published Listings Section -->
        <div class="section">
            <h5>İlanlarım</h5>
            <ul class="ilan-listesi">
                <?php
                // Assume $ilanlar contains user's published listings
                foreach ($ilanlar as $ilan): ?>
                    <li class="ilan-karti">
                        <img src="<?= htmlspecialchars($ilan['resim']) ?>" alt="ilan resmi" style="max-width: 200px;">
                        <div class="card-content">
                            <h3><?= htmlspecialchars($ilan['baslik']) ?></h3>
                            <p><strong>Kategori:</strong> <?= htmlspecialchars($ilan['kategori']) ?></p>
                            <p><strong>Tür:</strong> <?= htmlspecialchars($ilan['tur']) ?></p>
                            <p><strong>Fiyat:</strong> <?= htmlspecialchars($ilan['fiyat']) ?> TL</p>
							<p><strong>Açıklama:</strong> <?= htmlspecialchars($ilan['aciklama']) ?></p>
                        </div>
                        <div class="card-buttons">
                            <button class="btn edit-button" data-ilan-id="<?= $ilan['id'] ?>">Düzenle</button>
                            <button class="btn btn-danger delete-button" data-ilan-id="<?= $ilan['id'] ?>">Sil</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- User's Applied Listings Section -->
        <div class="section">
            <h5>Başvurularım</h5>
            <ul class="ilan-listesi">
                <?php
                // Assume $basvurular contains user's applied listings
                foreach ($basvurular as $basvuru): ?>
                    <li class="ilan-karti">
                        <img src="<?= htmlspecialchars($basvuru['resim']) ?>" alt="ilan resmi" style="max-width: 200px;">
                        <div class="card-content">
                            <h3><?= htmlspecialchars($basvuru['baslik']) ?></h3>
                            <p><strong>Kategori:</strong> <?= htmlspecialchars($basvuru['kategori']) ?></p>
                            <p><strong>Tür:</strong> <?= htmlspecialchars($basvuru['tur']) ?></p>
                            <p><strong>Fiyat:</strong> <?= htmlspecialchars($basvuru['fiyat']) ?> TL</p>
                            <p><strong>Mesajınız:</strong> <?= htmlspecialchars($basvuru['mesaj']) ?></p>
                        </div>
                        <div class="card-buttons">
                            <a href="basvuru_sil.php?id=<?= $basvuru['id'] ?>" class="btn btn-danger" onclick="return confirm('Bu başvuruyu silmek istediğinizden emin misiniz?');">Başvuruyu Sil</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
	</div>
   <script src="sayfa.js"></script>
</body>
</html>
<script>
// Düzenle butonuna tıklandığında
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function() {
            var ilanId = this.getAttribute('data-ilan-id');
            window.location.href = "ilan_duzenle.php?ilan_id=" + ilanId;
        });
    });

    // Sil butonuna tıklandığında
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
            var ilanId = this.getAttribute('data-ilan-id');
            if (confirm('Bu ilanı silmek istediğinizden emin misiniz?')) {
                window.location.href = "ilan_sil.php?ilan_id=" + ilanId;
            }
        });
    });
	
</script>