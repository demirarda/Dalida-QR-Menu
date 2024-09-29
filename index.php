<?php
$db = new SQLite3('db/menu.db');

// Kategorileri veritabanından çek
$categories = $db->query('SELECT * FROM categories');
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menü</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <a href="index.php"><img src="images/logo.png" alt="Logo" class="logo"></a>
        </div>
    </header>

    <section class="banner">
        <h1>Menü</h1>
        <p>Anasayfa > Menü</p>
    </section>

    <section class="menu-list">
        <?php while ($category = $categories->fetchArray()): ?>
            <a href="atistirmalik.php?category_id=<?php echo $category['id']; ?>">
                <div class="menu-item">
                    <span><?php echo $category['name']; ?></span>
                </div>
            </a>
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
