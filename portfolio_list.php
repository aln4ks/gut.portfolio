<?php
session_start();
include("database.php");

if (!isset($_SESSION['auth']) || empty($_SESSION['auth'])) {
    header("Location: login.php");
    exit;
}

$_SESSION['role'];
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
} else {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="media.css">
    <title>Просмотр портфолио</title>
</head>
<body>
    <header class="header-login">
        <div class="container">
            <nav class="navbar-login">
                <h1 class="text-h1" style="text-align:left; margin: 0; font-size: 36px; float: left">gut.portfolio</h1>
                <?php
                if ($role === 'student') {
                    echo '<a href="student_portfolio.php" class="navwords-login">Личный кабинет</a>';
                }
                ?>
                <a href="logout.php" class="navwords-login">Выход</a>
            </nav>
        </div>
    </header>

    <section class="portfolios">
        <div class="container">
            <?php
                $query = "SELECT id, name, surname, direction, course FROM users";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $name = $row['name'];
                        $surname = $row['surname'];
                        $directionId = $row['direction'];
                        $courseId = $row['course'];
                        $userId = $row['id'];
                
                        $directionQuery = "SELECT name FROM directions WHERE id = $directionId";
                        $directionResult = $conn->query($directionQuery);
                        $directionRow = $directionResult->fetch_assoc();
                        $directionName = $directionRow['name'];
                
                        $courseQuery = "SELECT name FROM courses WHERE id = $courseId";
                        $courseResult = $conn->query($courseQuery);
                        $courseRow = $courseResult->fetch_assoc();
                        $courseName = $courseRow['name'];
                
                        echo '<a href="student_details.php?id=' . $userId . '"> 
                        <div class="card-portfolio">
                        <h1 class="h1-port">' . $name . ' ' . $surname . '</h1>
                        <p class="p-port">Направление: ' . $directionName . '</p>
                        <p class="p-port">Курс: ' . $courseName . '</p>
                        </div>
                        </a>';
                    }
                } else {
                    echo '<p class="no-results">Нет результатов</p>';
                }
                $conn->close();
            ?>
        </div>
    </section>

    <footer class="footer">
        <p class="text-p-footer">&copy; GUT.PORTFOLIO 2023</p>
    </footer>
</body>
</html>