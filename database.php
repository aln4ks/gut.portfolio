<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'gut.portfolio';

$conn = mysqli_connect($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}