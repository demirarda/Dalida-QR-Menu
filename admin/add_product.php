<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Veritabanı bağlantısı
$db = new SQLite3('../db/menu.db');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Zorunlu alanları kontrol et
    if (empty($_POST['name']) || empty($_POST['price']) || empty($_POST['category_id'])) {
        echo "Ürün adı, fiyat ve kategori zorunludur!";
        exit;
    }

    $name = $_POST['name'];
    $description = $_POST['description'] ?? ''; // Zorunlu değil
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Resim dosyası yükleme işlemi
    $image_url = ''; // Resim olmadan eklenebilir
    if (!empty($_FILES["product_image"]["name"])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Sadece belirli dosya türlerine izin veriyoruz
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "Sadece JPG, JPEG, PNG ve GIF formatlarında resimler yüklenebilir.";
            exit;
        }

        // Dosya boyutu kontrolü (5MB maksimum)
        if ($_FILES["product_image"]["size"] > 5000000) {
            echo "Dosya boyutu 5MB'dan küçük olmalıdır.";
            exit;
        }

        // Dosyayı yükle
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $image_url = 'uploads/' . basename($_FILES["product_image"]["name"]);
        } else {
            echo "Resim yüklenirken hata oluştu!";
            exit;
        }
    }

    // Ürünü veritabanına kaydet
    $stmt = $db->prepare('INSERT INTO products (name, description, price, category_id, image_url) VALUES (:name, :description, :price, :category_id, :image_url)');
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':description', $description, SQLITE3_TEXT);
    $stmt->bindValue(':price', $price, SQLITE3_FLOAT);
    $stmt->bindValue(':category_id', $category_id, SQLITE3_INTEGER);
    $stmt->bindValue(':image_url', $image_url, SQLITE3_TEXT);
    $stmt->execute();

    header('Location: dashboard.php');
}
?>
