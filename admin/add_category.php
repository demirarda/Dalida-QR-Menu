<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$db = new SQLite3('../db/menu.db');

// Kategori ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    // Resim dosyası yükleme
    $image_url = '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Sadece belirli dosya türlerine izin verelim (jpg, png, jpeg, gif)
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = 'uploads/' . basename($_FILES['image']['name']); // Resim yolunu ayarlama
            } else {
                echo "Resim yüklenirken bir hata oluştu!";
            }
        } else {
            echo "Sadece JPG, JPEG, PNG ve GIF dosyalarına izin verilir.";
        }
    }

    // Kategoriyi veritabanına ekleme
    $stmt = $db->prepare('INSERT INTO categories (name, image_url) VALUES (:name, :image_url)');
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':image_url', $image_url, SQLITE3_TEXT);
    $stmt->execute();

    header('Location: dashboard.php?page=kategoriler');
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Ekle</title>
    <link rel="stylesheet" href="../css/admin_styles.css"> <!-- CSS dosyasını ekliyoruz -->
</head>
<body>
<div class="content">
    <h1>Kategori Ekle</h1>
    <form method="POST" action="add_category.php" enctype="multipart/form-data">
        <label>Kategori Adı:</label>
        <input type="text" name="name" required>

        <label>Resim Yükle:</label>
        <input type="file" name="image" accept="image/*">

        <input type="submit" value="Ekle">
    </form>
</div>
</body>
</html>
