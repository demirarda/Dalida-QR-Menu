<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$db = new SQLite3('../db/menu.db');

// Kategorileri çek
$categories = $db->query('SELECT * FROM categories');

// Ürünleri listelemek için kategori id'si
$selected_category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
$products = [];

if ($selected_category_id) {
    $products = $db->query("SELECT * FROM products WHERE category_id = $selected_category_id");
}

// Sayfa kontrolü (kategori ekle, ürün ekle)
$page = isset($_GET['page']) ? $_GET['page'] : 'kategoriler';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="../css/admin_styles.css"> <!-- CSS dosyasını bağlıyoruz -->
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Paneli</h2>
        <a href="dashboard.php?page=kategoriler">Mevcut Kategoriler</a>
        <a href="dashboard.php?page=kategori_ekle">Kategori Ekle</a>
        <a href="dashboard.php?page=urun_ekle">Ürün Ekle</a>
    </div>

    <!-- İçerik -->
    <div class="content">
        <?php if ($page == 'kategoriler'): ?>
            <h1>Mevcut Kategoriler</h1>
            <div class="category-list">
                <?php while ($category = $categories->fetchArray()): ?>
                    <div class="category-item">
                        <span><?php echo $category['name']; ?></span>
                        <div>
                            <a href="dashboard.php?category_id=<?php echo $category['id']; ?>">
                                <button>Ürünleri Listele</button>
                            </a>
                            <a href="edit_category.php?id=<?php echo $category['id']; ?>" class="edit-btn">Güncelle</a>
                            <a href="delete_category.php?id=<?php echo $category['id']; ?>" class="delete-btn" onclick="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?');">Sil</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php if ($selected_category_id && $products): ?>
                <div class="product-list">
                    <h3>Ürünler</h3>
                    <?php while ($product = $products->fetchArray()): ?>
                        <div class="product-item">
                            <span><?php echo $product['name']; ?> - <?php echo $product['price']; ?> TL</span>
                            <div>
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="edit-btn">Güncelle</a>
                                <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="delete-btn" onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">Sil</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

        <?php elseif ($page == 'kategori_ekle'): ?>
            <h1>Kategori Ekle</h1>
            <form method="POST" action="add_category.php">
                <label>Kategori Adı:</label>
                <input type="text" name="name" required>
                <input type="submit" value="Ekle">
            </form>

        <?php elseif ($page == 'urun_ekle'): ?>
            <h1>Ürün Ekle</h1>
            <form method="POST" action="add_product.php" enctype="multipart/form-data">
                <label>Ürün Adı:</label>
                <input type="text" name="name" required>
                <label>Açıklama:</label>
                <input type="text" name="description">
                <label>Fiyat:</label>
                <input type="number" step="0.01" name="price" required>
                <label>Kategori:</label>
                <select name="category_id" required>
                    <?php
                    $categories = $db->query('SELECT * FROM categories');
                    while ($category = $categories->fetchArray()):
                    ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php endwhile; ?>
                </select>
                <label>Resim Yükle (isteğe bağlı):</label>
                <input type="file" name="product_image" accept="image/*">
                <input type="submit" value="Ürün Ekle">
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
