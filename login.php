<?php
include("database.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $studentQuery = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($studentQuery);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['auth'] = true;
            $_SESSION['id'] = $row['id']; 
            $_SESSION['role'] = 'student';
            header("Location: portfolio_list.php");
            exit;
        } else {
            echo '<script>alert("Неверный пароль!");</script>';
        }
    } else {
        $employerQuery = "SELECT * FROM employers WHERE username = '$username'";
        $result = $conn->query($employerQuery);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['auth'] = true;
                $_SESSION['id'] = $row['id']; 
                $_SESSION['role'] = 'employer';
                header("Location: portfolio_list.php");
                exit;
            } else {
                echo '<script>alert("Неверный пароль!");</script>';
            }
        } else {
            echo '<script>alert("Пользователь не найден!");</script>';
        }
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="media.css">
    <title>Вход</title>
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="index.html" class="navwords" style="float: left;">Главная страница</a>
            </nav>
            <h1 class="text-h1">вход в систему</h1>
        </div>
    </header>

    <section class="register">
        <div class="reg-window">
            <form action="" method="post" id="reg_form">
                <label for="username" class="text-h3">Введите имя пользователя</label>
                <br>
                <input type="text" name="username" placeholder="Имя пользователя" required>
                <br>
                <label for="password" class="text-h3">Введите пароль</label>
                <br>
                <input type="password" name="password" placeholder="Пароль" required>
                <br>
                <button type="submit" class="btn-reg-2">Войти</button>
            </form>
        </div>
    </section>

    <footer class="footer">
        <p class="text-p-footer">&copy; GUT.PORTFOLIO 2023</p>
    </footer>
</body>
</html>