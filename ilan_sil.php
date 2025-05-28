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

// Gelen ilan ID'sini al ve kontrol et
if (!isset($_GET['ilan_id']) || !is_numeric($_GET['ilan_id'])) {
    die("Geçersiz ilan ID!");
}

$ilan_id = intval($_GET['ilan_id']);

// İlanın sahibi kullanıcı mı kontrol et
$check_query = $conn->prepare("SELECT * FROM ilanlar WHERE id = ? AND user_id = ?");
$check_query->bind_param("ii", $ilan_id, $user_id);
$check_query->execute();
$result = $check_query->get_result();

if ($result->num_rows === 0) {
    die("Bu ilan sizin tarafınızdan eklenmemiş veya bulunamadı.");
}

// İlanı sil
$delete_query = $conn->prepare("DELETE FROM ilanlar WHERE id = ?");
$delete_query->bind_param("i", $ilan_id);
if ($delete_query->execute()) {
    echo "İlan başarıyla silindi!";
} else {
    echo "İlan silinirken bir hata oluştu!";
}

// Yönlendirme
header("Location: profile.php");
exit;
?>
