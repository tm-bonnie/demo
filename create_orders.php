<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Подключение к базе данных
require 'db_connection.php'; // Убедитесь, что в этом файле создается объект $conn

// Пример списка товаров (в реальной системе данные должны быть из базы данных)
$products = [
    'Iphone 13 PRO',
    'AirPods PRO 2',
    'Macbook Air M2',
    'AirMax 4',
];

// Переменная для хранения сообщения об успехе или ошибке
$message = '';

// Проверка, был ли отправлен POST-запрос
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из формы
    $product_name = trim($_POST['product_name']);
    $quantity = (int)$_POST['quantity'];
    $delivery_address = trim($_POST['delivery_address']);
    $user_id = $_SESSION['user_id']; // Идентификатор пользователя из сессии

    // Проверка на положительное количество
    if ($quantity <= 0) {
        $message = "Ошибка: Количество должно быть больше нуля.";
    } else {
        // Проверка существования пользователя
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($result->num_rows === 0) {
            // Пользователь не найден
            header("Location: register.php?error=Пользователь не найден. Пожалуйста, зарегистрируйтесь.");
            exit();
        } else {
            // Подготовка и выполнение SQL-запроса для создания заказа
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, product_name, quantity, delivery_address) VALUES (?, ?, ?, ?)");
           

            if ($stmt->execute([$user_id, $product_name, $quantity, $delivery_address])) {
                $message = "Заказ успешно создан!";
            } else {
                $message = "Ошибка: " . $stmt->error; // Вывод ошибки
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Формирование заказа</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Формирование заказа</h1>
        
        <!-- Вывод сообщения об успехе или ошибке -->
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="create_orders.php" method="POST">
            <select name="product_name" required>
                <option value="">Выберите товар</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?php echo htmlspecialchars($product); ?>"><?php echo htmlspecialchars($product); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="quantity" placeholder="Количество" required>
            <input type="text" name="delivery_address" placeholder="Адрес доставки" required>
            <button type="submit">Сформировать заказ</button>
        </form>
    </div>
</body>
</html>
