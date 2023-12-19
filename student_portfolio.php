<?php
include("database.php");

// Проверяем, авторизован ли пользователь
session_start();
if (!isset($_SESSION['auth']) || empty($_SESSION['auth']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php"); // Перенаправляем на страницу авторизации, если пользователь не авторизован или не является студентом
    exit;
}

// Получаем информацию о пользователе из базы данных
$userId = $_SESSION['id'];

$userQuery = "SELECT * FROM users WHERE id = '$userId'";
$result = $conn->query($userQuery);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $name = $user['name'];
    $surname = $user['surname'];
    $middleName = $user['middle_name'];
    $direction = $user['direction'];
    $course = $user['course'];
    $about = $user['about'];
    $contact = $user['contact'];
    $achievements = $user['achievements'];
    $photo = $user['photo'];
} else {
    // Обработка ошибки, если пользователь не найден
    echo "Ошибка: Пользователь не найден.";
    exit;
}

$directionQuery = "SELECT name FROM directions WHERE id = '$direction'";
$directionResult = $conn->query($directionQuery);
$directionName = "";
if ($directionResult->num_rows == 1) {
    $directionRow = $directionResult->fetch_assoc();
    $directionName = $directionRow['name'];
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
    <title>Просмотр профиля</title>
</head>
<body>
<header class="header-login">
    <div class="container">
        <nav class="navbar-login">
            <h1 class="text-h1" style="text-align:left; margin: 0; font-size: 36px; float: left">gut.portfolio</h1>
            <a href="portfolio_list.php" class="navwords-login">Список портфолио</a>
            <a href="logout.php" class="navwords-login">Выход</a>
        </nav>
    </div>
</header>

<div class="container-2">
    <section class="view-portfolio">
        <div class="info-main">
            <?php
            if (!empty($photo)) {
                echo '<img class="photo-port" src="' . $photo . '" alt="Фото студента">';
            }
            ?>
        <div class="main-info">
            <h1><?php echo $surname . ' ' . $name . ' ' . $middleName; ?></h1>
            <br><br><br>
            <p class="text-p"><strong>Направление:</strong> <br> <?php echo $directionName; ?></p>
            <br><br>
            <p class="text-p"><strong>Курс:</strong> <br> <?php echo $course; ?></p>
            <br><br>
            <p class="text-p"><strong>О себе:</strong> <br> <?php echo $about; ?></p>
        </div>
        </div>
            <br>
        <div class="ca">
            <p class="text-p"><strong>Контактная информация:</strong> <br> <?php echo $contact; ?></p>
            <br>
            <?php
            if (!empty($achievements)) {
                $achievementPaths = explode(',', $achievements);
                echo '<p class="text-p"><strong>Достижения:<strong></p>';
                foreach ($achievementPaths as $path) {
                    $fileName = basename($path);
                    echo '<a href="' . $path . '" target="_blank">' . $fileName . '</a><br>';
                }
            }
            ?>
        </div>
            <br><br>
            <a href="student_cabinet.php"><button class="btn-reg-2">Редактировать</button></a>
    </section>
</div>

<footer class="footer">
    <p class="text-p-footer">&copy; GUT.PORTFOLIO 2023</p>
</footer>
</body>
</html>