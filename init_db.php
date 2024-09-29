<?php
$db = new SQLite3('db/menu.db');

// Tabloları oluştur
$db->exec("CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    image_url TEXT)");

$db->exec("CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    price REAL NOT NULL,
    category_id INTEGER,
    image_url TEXT,
    FOREIGN KEY (category_id) REFERENCES categories(id))");

echo "Veritabanı ve tablolar oluşturuldu!";
?>
