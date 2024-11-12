<?php
session_start();
require 'db_connection.php';

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Получение всех заказов
$query = "SELECT o.*, u.full_name, u.email FROM orders o JOIN users u ON o.user_id = u.id";
$stmt = $pdo->query($query);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Обработка обновления статуса заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];

    $update_stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $update_stmt->execute([$status, $order_id]);
    header("Location: admin_orders.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель администратора</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<button type="submit" onclick="document.location='index.php'">Назад</button>
    <div class="container">
        <h1>Все заказы</h1>

        <!-- Форма для выхода -->


        <table>
            <tr>
                <th>ФИО пользователя</th>
                <th>Email</th>
                <th>Наименование товара</th>
                <th>Количество</th>
                <th>Статус</th>
                <th>Изменить статус</th>
            </tr>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="6">Нет заказов для отображения.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['email']); ?></td>
                        <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td>
                            <form action="admin_orders.php" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" required>
                                    <option value="новое" <?php echo $order['status'] == 'новое' ? 'selected' : ''; ?>>Новое</option>
                                    <option value="подтверждено" <?php echo $order['status'] == 'подтверждено' ? 'selected' : ''; ?>>Подтверждено</option>
                                    <option value="отменено" <?php echo $order['status'] == 'отменено' ? 'selected' : ''; ?>>Отменено</option>
                                </select>
                                <button type="submit">Обновить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>

