<?php
session_start();
include 'db.php'; // Veritabanı bağlantısını buradan sağlayın

// Kullanıcı giriş yapmış mı kontrol et
if (!isset($_SESSION['username'])) {
    header("Location: giris.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $baslik = mysqli_real_escape_string($conn, $_POST['baslik']);
	$kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $konum = mysqli_real_escape_string($conn, $_POST['konum']);
    $fiyat = floatval($_POST['fiyat']);
    $aciklama = mysqli_real_escape_string($conn, $_POST['aciklama']);
    $tur = mysqli_real_escape_string($conn, $_POST['tur']);
    $username = $_SESSION['username'];  // Oturumdan username alıyoruz

// username ile user_id'yi bulalım
    $query_user = "SELECT id FROM users WHERE username = '$username'";
    $result_user = mysqli_query($conn, $query_user);
    // Tür bazlı varsayılan resimler
    $resim = '';
    if ($tur === 'konut') {
        $resim = 'images/konut.jpg';
    } elseif ($tur === 'arsa') {
        $resim = 'images/arsa.jpg';
    } elseif ($tur === 'ofis') {
        $resim = 'images/ofis.jpg';
    } elseif ($tur === 'dukkan'){
		$resim = 'images/dukkan.jpg';
	} elseif ($tur === 'kafe'){
		$resim = 'images/kafe.jpg';
	} elseif ($tur === 'restoran'){
		$resim = 'images/restoran.jpeg';
	}elseif ($tur === 'proje'){
		$resim = 'images/proje.jpeg';
	}

    if ($result_user && mysqli_num_rows($result_user) == 1) {
        $user = mysqli_fetch_assoc($result_user);
        $user_id = $user['id'];  // Kullanıcı id'sini aldık
		
        // SQL sorgusu
        $query = "INSERT INTO ilanlar (baslik, kategori, konum, fiyat, aciklama, user_id, tur, resim) 
                  VALUES ('$baslik', '$kategori', '$konum', '$fiyat', '$aciklama', $user_id, '$tur', '$resim')";

        // Sorguyu çalıştır
        if (mysqli_query($conn, $query)) {
            echo "<script>
                alert('İlan başarıyla eklendi!');
                window.location.href = 'anasayfa.php';
            </script>";
        } else {
            echo "İlan ekleme başarısız oldu: " . mysqli_error($conn);
        }
    } else {
        echo "Kullanıcı bulunamadı.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İlan Ekle</title>
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
        <h4>Yeni İlan Ekle</h4>
        <form method="POST" action="ilan_ekle.php">
            <div class="form-group">
                <label for="baslik">Başlık:</label>
                <input type="text" id="baslik" name="baslik" required>
            </div>
            
            <div class="form-group">
                <label for="aciklama">Açıklama:</label>
                <textarea id="aciklama" name="aciklama" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="kategori">Kategori:</label>
                <select id="kategori" name="kategori" required>
                    <option value="satilik">Satılık</option>
                    <option value="kiralik">Kiralık</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="tur">Tür:</label>
                <select id="tur" name="tur" required>
                    <option value="konut">Konut</option>
                    <option value="arsa">Arsa</option>
                    <option value="ofis">İşyeri</option>
					<option value="dukkan">Dükkan</option>
					<option value="kafe">Kafe</option>
					<option value="restoran">restoran</option>
					<option value="proje">proje</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="konum">Konum:</label>
                <input type="text" id="konum" name="konum" required>
            </div>
            
            <div class="form-group">
                <label for="fiyat">Fiyat (TL):</label>
                <input type="number" id="fiyat" step="0.01" name="fiyat" required>
            </div>
            
            <button type="submit" class="btn">İlan Ekle</button>
        </form>
    </div>
</div>
<script src="sayfa.js"></script>
</body>
</html>
