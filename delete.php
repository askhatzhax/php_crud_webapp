<?php
// Подключаем файл с конфигами и класс User
require 'config.php';
require 'User.php';
// Создаем обьект класса
$accountObj = new User($pdo);
// Создаем ассоциативный массив для сырых данных для json
$data = json_decode(file_get_contents("php://input"), true);
// Проверяем id в массиве и вызываем метод для удаления 
if (isset($data['id']) && $accountObj->deleteUser($data['id'])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка удаления']);
}
?>