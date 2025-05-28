<?php
session_start();

// Veritabanı bağlantısı
$host = "localhost";
$username = "root";
$password = "12345678";
$database = "emlak";
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ilan_id'])) {
    $ilan_id = intval($_POST['ilan_id']);
    $user_id = $_SESSION['user_id'];

    $query = "DELETE FROM favoriler WHERE ilan_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $ilan_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "İlan favorilerden başarıyla silindi!";
    } else {
        echo "İlan favorilerden silinemedi.";
    }
    $stmt->close();
}

// Favoriler sayfasına yönlendirme
header("Location: favoriler.php");
exit;
?>
