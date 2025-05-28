<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmlakNet</title>
    <link rel="icon" type="image/jpeg" href="images/ev.jpeg">
    <link rel="stylesheet" href="stil.css?v=2.0">
</head>
<body>
    <!-- Üst Menü -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>
                    <img src="images/ev.jpeg" alt="EmlakNet Logo" style="width: 40px; height: 40px; border-radius: 50%; vertical-align: middle;">
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

    <!-- Sayfa İçeriği -->
    <section class="search-section">
        <div class="arama-container">
            <h2>Satılık ev arıyorsan çözüm net: EmlakNet</h2>
			<form action="arama.php" method="GET" class="arama-alani">
				<select name="type" class="dropdown">
				<option value="konut">Konut</option>
				<option value="arsa">Arsa</option>
				<option value="ofis">Ofis</option>
				<option value="dukkan">Dükkan</option>
				<option value="kafe">Kafe</option>
				<option value="restoran">Restoran</option>
				<option value="proje">Proje</option>
				</select>
			<input type="text" name="query" placeholder="İl, ilçe, mahalle..." class="search-box">
			<button type="submit" class="btn-search">Ara</button>
			</form>
        </div>
    </section>
 <!-- Kayıt Ol Modalı -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <h2>Kayıt Ol</h2>
            <form id="registerForm">
                <label>Kullanıcı Adı:</label>
                <input type="text" name="username" required>
                <label>Email:</label>
                <input type="email" name="email" required>
                <label>Şifre:</label>
                <input type="password" name="password" required>
                <label>Rol:</label>
                <input type="text" name="role" required>
				<label>Telefon:</label>
                <input type="text" name="cep_tel" required>
                <button type="submit">Kayıt Ol</button>
            </form>
            <p>Zaten hesabınız var mı? <a href="javascript:void(0)" id="toLogin">Giriş Yap</a></p>
            <span class="close-btn">&times;</span>
        </div>
    </div>

    <!-- Giriş Yap Modalı -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <h2>Giriş Yap</h2>
            <form id="loginForm">
                <label>Kullanıcı Adı:</label>
                <input type="text" name="username" required>
                <label>Şifre:</label>
                <input type="password" name="password" required>
                <button type="submit">Giriş Yap</button>
            </form>
            <p>Hesabınız yok mu? <a href="javascript:void(0)" id="toRegister">Kayıt Ol</a></p>
            <span class="close-btn">&times;</span>
        </div>
    </div>

<script src="sayfa.js"></script>

</body>
</html>
