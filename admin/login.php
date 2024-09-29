<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kullanıcı adı ve şifreyi kontrol ediyoruz
    if ($username === 'admin' && $password === 'password') {
        // Kullanıcıyı oturum açmış olarak işaretle
        $_SESSION['loggedin'] = true;
        // Admin paneline yönlendir
        header('Location: dashboard.php');
        exit;
    } else {
        // Hatalı giriş mesajı
        echo "Hatalı kullanıcı adı veya şifre!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="../css/admin_styles.css"> <!-- CSS dosyasını ekliyoruz -->
</head>
<body>
    <div class="content">
        <h1>Giriş Yap</h1>
<form method="POST" action="login.php">
    <label for="username">Kullanıcı Adı:</label>
    <input type="text" id="username" name="username">
    <label for="password">Şifre:</label>
    <input type="password" id="password" name="password">
    <input type="submit" value="Giriş Yap">
</form>
</div>
</body>
</html>