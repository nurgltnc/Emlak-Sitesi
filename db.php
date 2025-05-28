<?php
$servername = "localhost";
$username = "root"; // MySQL kullanıcı adı
$password = "12345678";     // MySQL şifresi (genellikle AppServ'de boş bırakılır)
$database = "emlak"; // Veritabanı adınız

// Bağlantıyı oluştur
$conn = mysqli_connect($servername, $username, $password, $database);

// Bağlantıyı kontrol et
if (!$conn) {
    die("Veritabanına bağlanılamadı: " . mysqli_connect_error());
}
?>
