<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$db = new SQLite3('../db/menu.db');
$product_id = $_GET['id'];

// Ürünü sil
$stmt = $db->prepare('DELETE FROM products WHERE id = :id');
$stmt->bindValue(':id', $product_id, SQLITE3_INTEGER);
$stmt->execute();

header('Location: dashboard.php');
?>
