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
} else {
    // Обработка ошибки, если пользователь не найден
    echo "Ошибка: Пользователь не найден.";
    exit;
}

$directionsQuery = "SELECT * FROM directions";
$directionsResult = $conn->query($directionsQuery);


// Обновление профиля пользователя
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newName = $_POST['name'];
    $newSurname = $_POST['surname'];
    $newMiddleName = $_POST['middle_name'];
    $newDirection = $_POST['direction'];
    $newCourse = $_POST['course'];
    $newAbout = $_POST['about'];
    $newContact = $_POST['contact'];

    // Обработка загруженных файлов
    $achievementsDir = "achievements/"; // Директория, куда будут сохраняться файлы достижений
    $achievementsPaths = array();

    // Обработка загруженной фотографии
    $photoDir = "photos/"; // Директория, куда будет сохраняться фотография пользователя

    if (!empty($_FILES['photo']['name'])) {
        $photoFileName = $_FILES['photo']['name'];
        $photoTmpFilePath = $_FILES['photo']['tmp_name'];
        $newPhotoFilePath = $photoDir . $photoFileName;

        if (move_uploaded_file($photoTmpFilePath, $newPhotoFilePath)) {
            // Сохраняем путь к файлу в базе данных
            $updatePhotoQuery = "UPDATE users SET photo = '$newPhotoFilePath' WHERE id = '$userId'";
            if ($conn->query($updatePhotoQuery) !== TRUE) {
                echo "Ошибка: " . $conn->error;
                exit;
            }
        }
    }

    if (!empty($_FILES['achievements']['name'][0])) {
        // Создаем директорию для сохранения файлов, если еще не существует
        if (!file_exists($achievementsDir)) {
            mkdir($achievementsDir, 0777, true);
        }

        foreach ($_FILES['achievements']['name'] as $index => $filename) {
            $tmpFilePath = $_FILES['achievements']['tmp_name'][$index];
            $newFilePath = $achievementsDir . $filename;

            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                // Сохраняем путь к файлу в массиве
                $achievementsPaths[] = $newFilePath;
            }
        }
    }

    // Преобразуем массив с путями к файлам в строку, разделяя их запятыми
    $achievementsString = implode(',', $achievementsPaths);

    // Обновляем запись в базе данных
    $updateQuery = "UPDATE users SET name = '$newName', surname = '$newSurname', middle_name = '$newMiddleName', direction = '$newDirection', course = '$newCourse', about = '$newAbout', achievements = '$achievementsString', contact = '$newContact' WHERE id = '$userId'";

    if ($conn->query($updateQuery) === TRUE) {
        echo '<script>alert("Профиль успешно обновлен!"); window.location.href = "student_portfolio.php";</script>';
        // Можно добавить дополнительные действия, такие как перенаправление на другую страницу
    } else {
        echo "Ошибка: " . $conn->error;
        exit;
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
    <title>Редактирование профиля</title>
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

<div class="container">
    <section class="edit-port">
            <form action="" method="post" id="port_form" enctype="multipart/form-data">
                <label for="name" class="text-h3">Имя:</label>
                <br>
                <input type="text" name="name" placeholder="Имя" value="<?php echo $name; ?>" required>
                <br>
                <label for="surname" class="text-h3">Фамилия:</label>
                <br>
                <input type="text" name="surname" placeholder="Фамилия" value="<?php echo $surname; ?>" required>
                <br>
                <label for="middle_name" class="text-h3">Отчество:</label>
                <br>
                <input type="text" name="middle_name" placeholder="Отчество" value="<?php echo $middleName; ?>" required>
                <br>
                <label for="direction" class="text-h3">Направление:</label>
                <br>
                <select class="form-select" name="direction" required>
                <?php
                // Выводим опции для выбора специальности
                while ($direction = $directionsResult->fetch_assoc()) {
                    $directionId = $direction['id'];
                    $directionName = $direction['name'];
                    echo "<option value=\"$directionId\"";
                    if ($direction == $directionId) echo ' selected';
                    echo ">$directionName</option>";
                }
                ?>
                </select>
                <br>
                <label for="course" class="text-h3">Курс:</label>
                <br>
                <select class="form-select" name="course" required>
                <?php
                // Выводим опции для выбора курса
                for ($i = 1; $i <= 5; $i++) {
                    echo "<option value=\"$i\"";
                    if ($i == $course) echo ' selected';
                    echo ">$i курс</option>";
                }
                ?>
                </select>
                <br>
                <label for="about" class="text-h3">О себе:</label>
                <br>
                <textarea name="about" placeholder="Расскажите о себе" required><?php echo $about; ?></textarea>
                <br>
                <label for="photo" class="text-h3">Фотография:</label>
                <br>
                <input class="input-file" type="file" name="photo">
                <br>
                <label for="achievements" class="text-h3">Достижения:</label>
                <br>
                <input class="input-file" type="file" name="achievements[]" multiple>
                <br>
                <label for="contact" class="text-h3">Контактная информация:</label>
                <br>
                <textarea name="contact" placeholder="Контактная информация" required><?php echo $contact; ?></textarea>
                <br>
                <button type="submit" class="btn-reg-2">Сохранить</button>
            </form>
    </section>
</div>
    <footer class="footer">
        <p class="text-p-footer">&copy; GUT.PORTFOLIO 2023</p>
    </footer>
</body>
</html>