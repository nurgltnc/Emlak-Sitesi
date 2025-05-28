<?php
session_start(); // Oturum açma işlemi burada bir kez yapılmalı.
include 'db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; 

    // Kullanıcıyı veritabanında arayalım
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Şifreyi doğruluyoruz
        if ($password === $user['password']) {
            // Başarılı giriş, kullanıcı bilgilerini session'a kaydediyoruz
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id']; // Kullanıcı ID'sini de kaydediyoruz

            // Yanıt olarak başarılı mesajı gönderiyoruz
            $response['success'] = true;
            $response['message'] = "Giriş başarılı!";
        } else {
            // Geçersiz şifre
            $response['success'] = false;
            $response['message'] = "Geçersiz şifre.";
        }
    } else {
        // Kullanıcı bulunamadı
        $response['success'] = false;
        $response['message'] = "Kullanıcı bulunamadı.";
    }
}

// JSON formatında cevap döndürüyoruz
echo json_encode($response);
?>
