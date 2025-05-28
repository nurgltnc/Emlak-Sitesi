<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Lütfen giriş yapın!";
    exit;
}

$host = "localhost";
$username = "root";
$password = "12345678";
$database = "emlak";
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $cep_tel = $_POST['cep_tel'];
    $update_query = $conn->prepare("UPDATE users SET username = ?, email = ?, cep_tel = ? WHERE id = ?");
    $update_query->bind_param("sssi", $username, $email, $cep_tel, $user_id);

    if ($update_query->execute()) {
        echo "Bilgiler başarıyla güncellendi!";
		// Başarılı güncelleme sonrası yönlendirme
        header('Location: profile.php');
        exit();
    } else {
        echo "Bir hata oluştu: " . $conn->error;
    }
}

$user_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user = $user_query->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/jpeg" href="images/ev.jpeg">
    <link rel="stylesheet" href="stil.css">
    <title>Kullanıcı Bilgilerini Düzenle</title>
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
        <h4>Kullanıcı Bilgilerini Düzenle</h4>
        <form method="post">
            <div class="form-group">
                <label for="username">Ad:</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="cep_tel">Telefon:</label>
                <input type="text" name="cep_tel" id="cep_tel" value="<?= htmlspecialchars($user['cep_tel']) ?>" required>
            </div>

            <button type="submit" class="btn-submit">Güncelle</button>
        </form>
    </div>
</div>
<script src="sayfa.js"></script>
</body>
</html>
