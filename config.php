<?php
// Указываем данные для подключения к бд
$host = 'localhost';
$dbname = 'users';
$username = 'root';
$password = 'root';
//Создаем возможное исключение если возникнет ошибка при подключении к бд 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        // Включаем выброс исключений при ошибках
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Делаем вывод данных в виде ассоциативного массива
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>