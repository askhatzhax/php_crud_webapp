<?php
// Подключаем файл с конфигамии бд и класс User
require_once 'config.php'; 
require 'User.php';
// Создаём объект класса User
$accountObj = new User($pdo);
// Определяем количество записей на странице
$recordsPerPage = 10;
// Получаем текущее смещение
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;
// Получаем общее количество записей в базе
$stmt = $pdo->query("SELECT COUNT(*) FROM accounts");
$totalRecords = $stmt->fetchColumn();
// Рассчитываем общее количество страниц
$totalPages = ceil($totalRecords / $recordsPerPage);
// Получаем записи для текущей страницы
$stmt = $pdo->prepare("SELECT * FROM accounts LIMIT :limit OFFSET :offset");
// Количество записей на странице
$stmt->bindValue(':limit', $recordsPerPage, PDO::PARAM_INT);
// Смещение
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
// запрос sql
$stmt->execute();
// Получаем все найденные записи в виде ассоциативного массива
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<script>
    // Отправляем AJAX-запрос на delete.php
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            let accountId = this.getAttribute("data-id");

            fetch("delete.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id: accountId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let row = document.getElementById("row-" + accountId);
                    if (row) {
                        row.remove();
                    }
                    // Показываем сообщение об удалении
                    let msg = document.getElementById("success-message");
                    msg.style.display = "block";
                    setTimeout(() => msg.style.display = "none", 2000);
                } else {
                    alert("Ошибка: " + data.message);
                }
            })
            .catch(error => alert("Ошибка при удалении"));
        });
    });
});
</script>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список аккаунтов</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
<h2 class="mb-4">Список аккаунтов</h2>

 <!-- Кнопка "Добавить аккаунт" -->
 <div class="text-end mb-3">
        <a href="create.php" class="btn btn-success btn-lg">
        𖡌 Добавить аккаунт
        </a>
    </div>
<!-- Сообщение об успешном удалении -->
<div id="success-message" class="success-message">Аккаунт удалён!</div>
<!-- Создаем таблицу -->
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Email</th>
            <th>Компания</th>
            <th>Должность</th>
            <th>Телефоны</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <!-- Выводим все акаунты пользователей  -->
        <?php foreach ($accounts as $acc): ?>
            <tr id="row-<?= $acc['id'] ?>">
                <td><?= $acc['id'] ?></td>
                <td><?= $acc['first_name'] ?></td>
                <td><?= $acc['last_name'] ?></td>
                <td><?= $acc['email'] ?></td>
                <td><?= $acc['company_name'] ?></td>
                <td><?= $acc['position'] ?></td>
                <td>
                    <?= htmlspecialchars($acc['phone1'] ?? '-') ?>,
                    <?= htmlspecialchars($acc['phone2'] ?? '-') ?>,
                    <?= htmlspecialchars($acc['phone3'] ?? '-') ?>
                </td>
                <td>
                <a href="edit.php?id=<?= $acc['id'] ?>" class="btn btn-warning btn-sm"> ✎ Редактировать</a>
                <a href="#" class="btn btn-danger btn-sm delete-btn" data-id="<?= $acc['id'] ?>">✘ Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Пагинация -->
<nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>

</body>
</html>
