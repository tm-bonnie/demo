<?php
session_start();
require 'db_connection.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    if (empty($username) || empty($full_name) || empty($email)) {
        die("Ошибка: Все поля обязательны для заполнения.");
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        die("Ошибка: Имя пользователя или электронная почта уже существуют.");
    }

    $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, phone, email) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$username, $password, $full_name, $phone, $email])) {
        $message = "Регистрация прошла успешно!";
    } else {
        $message = "Ошибка: " . $stmt->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>
    <div class="container">
        <h1>Регистрация</h1>
        <?php if ($message): ?>
            <div><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="text" name="full_name" placeholder="ФИО" required>
            <input type="text" name="phone" placeholder="Телефон">
            <input type="email" name="email" placeholder="Электронная почта" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>
</body>
</html>
