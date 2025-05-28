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
$ilan_id = isset($_GET['ilan_id']) ? intval($_GET['ilan_id']) : 0;
$user_tel = $_SESSION['user_tel'];

// Kullanıcının mevcut telefon numarasını al
$sql_user = "SELECT cep_tel FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param('i', $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_cep_tel = "";
if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $user_cep_tel = $row_user['cep_tel'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kullanıcının seçtiği telefon numarası
    $user_tel = isset($_POST['use_existing_tel']) && $_POST['use_existing_tel'] === "yes" ? $user_cep_tel : $_POST['user_tel'];
    $mesaj = $_POST['mesaj'];
	
    // Veritabanına başvuruyu kaydet
    $sql = "INSERT INTO basvurular (user_id, user_tel, ilan_id, mesaj) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isis', $user_id, $user_tel, $ilan_id, $mesaj);

    if ($stmt->execute()) {
       echo "<script>
                alert('Başvurunuz başarıyla kaydedildi!');
                window.location.href = 'anasayfa.php';
            </script>";
    } else {
        echo "Başvurunuzu kaydederken bir hata oluştu: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İlana Başvur</title>
	<link rel="icon" type="image/jpeg" href="images/ev.jpeg">
    <link rel="stylesheet" href="stil.css">
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
    <div class="form-container">
        <h4>Başvuru Formu</h4>
        <form method="POST" action="ilan_basvur.php?ilan_id=<?= htmlspecialchars($ilan_id) ?>">
            <div class="form-group">
                <label for="mesaj">Mesajınız:</label>
                <textarea id="mesaj" name="mesaj" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
		<label>
        <input type="radio" name="use_existing_tel" value="yes" checked onclick="togglePhoneInput(true)">
        Mevcut telefon numaranızı kullanın: <strong><?= htmlspecialchars($user_cep_tel) ?></strong>
		</label>
		</div>

	<div class="form-group">
    <label>
        <input type="radio" name="use_existing_tel" value="no" onclick="togglePhoneInput(false)">
        Başka bir telefon numarası girin:
    </label>
    <input type="tel" id="user_tel" name="user_tel" placeholder="Yeni telefon numarası" pattern="[0-9]{11}">
	</div>

            <button type="submit" class="btn">Başvuruyu Gönder</button>
        </form>
    </div>
</div>
</body>
</html>
