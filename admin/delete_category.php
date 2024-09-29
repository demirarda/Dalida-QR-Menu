<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$db = new SQLite3('../db/menu.db');
$category_id = $_GET['id'];

// Kategoriyi sil
$stmt = $db->prepare('DELETE FROM categories WHERE id = :id');
$stmt->bindValue(':id', $category_id, SQLITE3_INTEGER);
$stmt->execute();

header('Location: dashboard.php');
?>
