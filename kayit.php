<?php
include 'db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // Şifreyi olduğu gibi alıyoruz
    $email = $_POST['email'];
    $role = $_POST['role']; // Kullanıcı rolü
	$cep_tel = $_POST['cep_tel'];

    // Kullanıcıyı veritabanına ekleyelim
    $query = "INSERT INTO users (username, password, email, role, cep_tel) VALUES ('$username', '$password', '$email', '$role', '$cep_tel')";
    if (mysqli_query($conn, $query)) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['message'] = "Kayıt oluşturulamadı: " . mysqli_error($conn);
    }
}

echo json_encode($response);
?>
