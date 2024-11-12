<?php
session_start();
// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Подключение к базе данных
require 'db_connection.php';

// Получение заказов пользователя
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои заказы</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Мои заказы</h1>
        <table>
            <tr>
                <th>Наименование товара</th>
                <th>Количество</th>
                <th>Статус</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="create_orders.php">Сформировать новый заказ</a>
    </div>
</body>
</html>

