<?php
$db = new SQLite3('db/menu.db');

// URL'den gelen category_id'yi alın
$category_id = $_GET['category_id'];

// Seçili kategoriye ait ürünleri çek
$products = $db->query("SELECT * FROM products WHERE category_id = $category_id");
$category = $db->querySingle("SELECT * FROM categories WHERE id = $category_id", true);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $category['name']; ?></title>
    <link rel="stylesheet" href="css/aperatif.css">
</head>
<body>
    <header>
        <div class="header-container">
            <a href="index.php">
                <img src="images/logo.png" alt="Balkon Cafe Logo" class="logo">
            </a>
        </div>
    </header>

    <section class="summer-menu-banner">
        <h1><?php echo $category['name']; ?></h1>
        <p>Anasayfa > Menü > <?php echo $category['name']; ?></p>
    </section>

    <section class="summer-menu-list">
        <?php while ($product = $products->fetchArray()): ?>
            <div class="summer-menu-item">
                <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?> Fotoğrafı" class="menu-photo">
                <div class="menu-details">
                    <h2><?php echo $product['name']; ?></h2>
                    <span class="price"><?php echo $product['price']; ?> TL</span>
                    <p><?php echo $product['description']; ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </section>

    <footer>
        <div class="footer-container">
            <p><strong>Adres:</strong> Şirinevler, İncesu Sk., 34188 Bahçelievler/İstanbul</p>
            <p><strong>Telefon:</strong> 0312 xx xxx-xx <br>0544xxxxxx</p>
        </div>
    </footer>
</body>
</html>
