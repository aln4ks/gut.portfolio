<?php
include("database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkQuery = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        echo '<script>alert("Имя пользователя или email уже заняты.");</script>';
    } else {
        $checkEmployerQuery = "SELECT * FROM employers WHERE username = '$username' OR email = '$email'";
        $resultEmployer = $conn->query($checkEmployerQuery);

        if ($resultEmployer->num_rows > 0) {
            echo '<script>alert("Имя пользователя или email уже заняты.");</script>';
        } else {

            $registerQuery = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            if ($conn->query($registerQuery) === TRUE) {
                echo '<script>alert("Регистрация прошла успешно! Переходим на страницу входа."); window.location.href = "login.php";</script>';
            } else {
                echo "Ошибка регистрации: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="media.css">
    <title>Регистрация студента</title>
</head>

<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="index.html" class="navwords" style="float: left;">Главная страница</a>
                <a href="login.php" class="navwords" target="_blank">Вход</a>
            </nav>
            <h1 class="text-h1">регистрация студента</h1>
        </div>
    </header>

    <section class="register">
        <div class="container">
        <div class="reg-window">
            <form action="" method="post" id="reg_form">
                <label for="username" class="text-h3">имя пользователя</label>
                <br>
                <input type="text" name="username" placeholder="Введите имя пользователя" required>
                <br>
                <label for="email" class="text-h3">email</label>
                <br>
                <input type="email" name="email" placeholder="Введите email" required>
                <br>
                <label for="password" class="text-h3">пароль</label>
                <br>
                <input type="password" name="password" placeholder="Введите пароль" required>
                <br>
                <button type="submit" class="btn-reg-2">Зарегистрироваться</button>
            </form>

            <p class="text-p-center">Вы работодатель? <a class="a-reg" href="reg_emp.php">Регистрация</a></p>
        </div>
    </div>
    </section>

    <footer class="footer">
        <p class="text-p-footer">&copy; GUT.PORTFOLIO 2023</p>
    </footer>
</body>

</html>