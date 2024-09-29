<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$db = new SQLite3('../db/menu.db');

// Ürün id'sini alıyoruz
$product_id = isset($_GET['id']) ? $_GET['id'] : null;

// Ürünü veritabanından çekiyoruz
$product = $db->querySingle("SELECT * FROM products WHERE id = $product_id", true);

if (!$product) {
    echo "Ürün bulunamadı!";
    exit;
}

// Ürün güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Resim güncelleme kontrolü
    $image_url = $product['image_url']; // Mevcut resim
    if (!empty($_FILES['product_image']['name'])) {
        // Yeni resim dosyasını yükleyelim
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES['product_image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Sadece belirli dosya türlerine izin verelim (jpg, png, jpeg, gif)
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                $image_url = 'uploads/' . basename($_FILES['product_image']['name']); // Yeni resim yolunu ayarla
            } else {
                echo "Resim yüklenirken bir hata oluştu!";
            }
        } else {
            echo "Sadece JPG, JPEG, PNG ve GIF dosyalarına izin verilir.";
        }
    }

    // Ürün güncelleme işlemi
    $stmt = $db->prepare("UPDATE products SET name = :name, description = :description, price = :price, category_id = :category_id, image_url = :image_url WHERE id = :id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':description', $description, SQLITE3_TEXT);
    $stmt->bindValue(':price', $price, SQLITE3_FLOAT);
    $stmt->bindValue(':category_id', $category_id, SQLITE3_INTEGER);
    $stmt->bindValue(':image_url', $image_url, SQLITE3_TEXT);
    $stmt->bindValue(':id', $product_id, SQLITE3_INTEGER);
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
    <title>Ürün Güncelle</title>
    <link rel="stylesheet" href="../css/admin_styles.css"> <!-- CSS dosyasını ekliyoruz -->
</head>
<body>
<div class="content">
    <h1>Ürün Güncelle</h1>
    <form method="POST" action="edit_product.php?id=<?php echo $product_id; ?>" enctype="multipart/form-data">
        <label>Ürün Adı:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

        <label>Açıklama:</label>
        <input type="text" name="description" value="<?php echo htmlspecialchars($product['description']); ?>">

        <label>Fiyat:</label>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>

        <label>Kategori:</label>
        <select name="category_id" required>
            <?php
            $categories = $db->query('SELECT * FROM categories');
            while ($category = $categories->fetchArray()):
            ?>
                <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Mevcut Resim:</label>
        <img src="<?php echo $product['image_url']; ?>" alt="Ürün Fotoğrafı" width="150">

        <label>Yeni Resim Yükle (isteğe bağlı):</label>
        <input type="file" name="product_image" accept="image/*">

        <input type="submit" value="Ürünü Güncelle">
    </form>
</div>
</body>
</html>
