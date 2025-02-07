<?php
// Подключаем файл с конфигами и класс User
require_once 'config.php';
require_once 'User.php';
// Создаем обьект класса и массив для ошибок
$userObj = new User($pdo);
$errors = [];
// Проверяем метод пост и зачищаем лишние проблемы с trim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $company_name = trim($_POST['company_name'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $phones = [
        trim($_POST['phone1'] ?? ''), 
        trim($_POST['phone2'] ?? ''), 
        trim($_POST['phone3'] ?? '')
    ];

    // Валидация данных
    if (empty($first_name) || empty($last_name) || empty($email)) {
        $errors[] = "Имя, фамилия и email обязательны для заполнения.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Некорректный email.";
    } else {
        // Добавляем пользователя через класс User переходим на index.php
        $result = $userObj->addUser($first_name, $last_name, $email, $company_name, $position, $phones);
        if ($result === true) {
            header("Location: index.php");
            exit;
        } else {
            $errors[] = $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить аккаунт</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2 class="mb-4">Добавить аккаунт</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label class="form-label">Имя *</label>
        <input type="text" name="first_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Фамилия *</label>
        <input type="text" name="last_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email *</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Компания</label>
        <input type="text" name="company_name" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Должность</label>
        <input type="text" name="position" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Телефон 1</label>
        <input type="text" name="phone1" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Телефон 2</label>
        <input type="text" name="phone2" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Телефон 3</label>
        <input type="text" name="phone3" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
    <a href="list.php" class="btn btn-secondary">Назад</a>
</form>

</body>
</html>
