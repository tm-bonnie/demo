<?php
$host = 'localhost';
$db = 'avoska';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Ошибка подключения PDO: " . $e->getMessage());
    die("Ошибка подключения к базе данных.");
}
?>
