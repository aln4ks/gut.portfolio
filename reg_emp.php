<?php
include("database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $company = $_POST['company'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkEmployerQuery = "SELECT * FROM employers WHERE username = '$username' OR email = '$email'";
    $resultEmployer = $conn->query($checkEmployerQuery);

    if ($resultEmployer->num_rows > 0) {
        echo '<script>alert("Имя пользователя или email уже заняты.");</script>';
    } else {
        $checkUserQuery = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $resultUser = $conn->query($checkUserQuery);

        if ($resultUser->num_rows > 0) {
            echo '<script>alert("Имя пользователя или email уже заняты.");</script>';
        } else {
            $registerQuery = "INSERT INTO employers (last_name, first_name, middle_name, company, username, email, password) VALUES ('$last_name', '$first_name', '$middle_name', '$company', '$username', '$email', '$password')";
            if ($conn->query($registerQuery) === TRUE) {
                echo '<script>alert("Регистрация прошла успешно! Переходим на страницу входа."); window.location.href = "login.php";</script>';
            } else {
                echo "Ошибка регистрации: " . $conn->error;
            }
        }
    }
}

$companyQuery = "SELECT * FROM company";
$companyResult = $conn->query($companyQuery);
$companies = array();
if ($companyResult->num_rows > 0) {
    while ($row = $companyResult->fetch_assoc()) {
        $companies[] = $row['company_name'];
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
    <title>Регистрация работодателя</title>
</head>

<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="index.html" class="navwords" style="float: left;">Главная страница</a>
                <a href="login.php" class="navwords" target="_blank">Вход</a>
            </nav>
            <h1 class="text-h1">регистрация работодателя</h1>
        </div>
    </header>

    <section class="register">
        <div class="container">
        <div class="reg-window">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="reg_form">
            <label for="last_name" class="text-h3">Фамилия</label>
            <input type="text" name="last_name" required placeholder="Введите фамилию"><br>
            <label for="first_name" class="text-h3">Имя</label>
            <br>
            <input type="text" name="first_name" required placeholder="Введите имя"><br>
            <label for="middle_name" class="text-h3">Отчество</label>
            <input type="text" name="middle_name" placeholder="Введите отчество"><br>
            <label for="company" class="text-h3">Компания:</label>
            <br>
            <select name="company" class="form-select" required>
                <?php foreach($companies as $companyName): ?>
                    <option value="<?php echo $companyName; ?>"><?php echo $companyName; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="username" class="text-h3">Введите имя пользователя</label>
            <br>
            <input type="text" name="username" placeholder="Введите имя пользователя" required>
            <br>
            <label for="email" class="text-h3">Введите email</label>
            <br>
            <input type="email" name="email" placeholder="Введите email" required>
            <br>                
            <label for="password" class="text-h3">Введите пароль</label>
            <br>
            <input type="password" name="password" placeholder="Введите пароль" required>
            <br>
            <button type="submit" class="btn-reg-2">Зарегистрироваться</button>
            </form>

            <p class="text-p-center">Вы студент? <a class="a-reg" href="reg_stud.php">Регистрация</a></p>
        </div>
    </div>
    </section>

    <footer class="footer">
        <p class="text-p-footer">&copy; GUT.PORTFOLIO 2023</p>
    </footer>
</body>

</html>