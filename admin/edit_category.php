<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$db = new SQLite3('../db/menu.db');
$category_id = $_GET['id'];

// Kategoriyi veritabanından çek
$category = $db->querySingle("SELECT * FROM categories WHERE id = $category_id", true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $image_url = $_POST['image_url'];

    // Kategoriyi güncelle
    $stmt = $db->prepare('UPDATE categories SET name = :name, image_url = :image_url WHERE id = :id');
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':image_url', $image_url, SQLITE3_TEXT);
    $stmt->bindValue(':id', $category_id, SQLITE3_INTEGER);
    $stmt->execute();

    header('Location: dashboard.php');
}
?>

<form method="POST" action="edit_category.php?id=<?php echo $category_id; ?>">
    <label>Kategori Adı:</label>
    <input type="text" name="name" value="<?php echo $category['name']; ?>">
    <input type="submit" value="Güncelle">
</form>
