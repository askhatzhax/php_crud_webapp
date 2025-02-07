<?php
// Подключаем файл с конфигамии бд и класс User
require 'config.php';
require 'User.php';
// Создаем обьект класса
$accountObj = new User($pdo);

// Получаем ID аккаунта из URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// Создаем переменную для ассоциативного массива
$account = null;

// Если ID передан и больше 0, пытаемся получить данные аккаунта
if ($id > 0) {
    // Подготавливаем SQL-запрос, который выбирает все столбцы из таблицы accounts, где id равен :id
    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE id = :id");
    // Подставляем значение $id вместо :id и выполняем запрос.
    $stmt->execute([':id' => $id]);
    // Получаем ассоциативный массив
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
} 

// Если форма отправлена то передаем данные из формы и переходим на index.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $account) {
    $first_name = $_POST['first_name'] ?? '';
    $last_name  = $_POST['last_name'] ?? '';
    $email      = $_POST['email'] ?? '';
    $company    = $_POST['company'] ?? '';
    $position   = $_POST['position'] ?? '';
    $phones     = [
        $_POST['phone1'] ?? '',
        $_POST['phone2'] ?? '',
        $_POST['phone3'] ?? ''
    ];
    if ($accountObj->updateUser($id, $first_name, $last_name, $email, $company, $position, $phones)) {
        header("Location: index.php?updated=1");
        exit;
    } else {
        $error = "Ошибка при обновлении аккаунта.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование аккаунта</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Редактировать аккаунт</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($account && is_array($account)): ?>
        <form method="post">
            <div class="mb-3">
                <label>Имя</label>
                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($account['first_name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label>Фамилия</label>
                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($account['last_name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($account['email'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label>Компания</label>
                <input type="text" name="company" class="form-control" value="<?= htmlspecialchars($account['company_name'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label>Должность</label>
                <input type="text" name="position" class="form-control" value="<?= htmlspecialchars($account['position'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label>Телефон 1</label>
                <input type="text" name="phone1" class="form-control" value="<?= htmlspecialchars($account['phone1'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label>Телефон 2</label>
                <input type="text" name="phone2" class="form-control" value="<?= htmlspecialchars($account['phone2'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label>Телефон 3</label>
                <input type="text" name="phone3" class="form-control" value="<?= htmlspecialchars($account['phone3'] ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="index.php" class="btn btn-secondary">Назад</a>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">Аккаунт не найден!</div>
    <?php endif; ?>
</div>
</body>
</html>
