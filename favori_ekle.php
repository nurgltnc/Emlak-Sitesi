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

// Kullanıcı giriş kontrolü
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Lütfen giriş yapın!";
    exit;
}

$user_id = $_SESSION['user_id'];
$ilan_id = isset($_GET['ilan_id']) ? intval($_GET['ilan_id']) : 0;
 // İlanın varlığını kontrol et
$check_ilan = "SELECT * FROM ilanlar WHERE id = $ilan_id";
$result_ilan = mysqli_query($conn, $check_ilan);

    if (mysqli_num_rows($result_ilan) > 0) {
        // İlan var, favorilere ekle
        $query_favori = "INSERT INTO favoriler (user_id, ilan_id) VALUES ($user_id, $ilan_id)";
        if (mysqli_query($conn, $query_favori)) {
           echo "<script>
                alert('İlan favorilere eklendi!');
                window.location.href = 'anasayfa.php';
            </script>";
        } else {
            echo "Favorilere eklerken bir hata oluştu.";
        }
    } else {
        echo "Geçersiz ilan ID'si.";
    }
?>