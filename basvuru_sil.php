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

// Gelen başvuru ID'sini al ve kontrol et
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Geçersiz başvuru ID!");
}

$basvuru_id = intval($_GET['id']);

// Başvurunun kullanıcının mı olduğunu kontrol et
$check_query = $conn->prepare("SELECT * FROM basvurular WHERE id = ? AND user_id = ?");
$check_query->bind_param("ii", $basvuru_id, $user_id);
$check_query->execute();
$result = $check_query->get_result();

if ($result->num_rows === 0) {
    die("Bu başvuru sizin tarafınızdan yapılmamış veya bulunamadı.");
}

// Başvuruyu sil
$delete_query = $conn->prepare("DELETE FROM basvurular WHERE id = ?");
$delete_query->bind_param("i", $basvuru_id);
if ($delete_query->execute()) {
    echo "Başvuru başarıyla silindi!";
} else {
    echo "Başvuru silinirken bir hata oluştu!";
}

// Yönlendirme
header("Location: profile.php");
exit;
?>
