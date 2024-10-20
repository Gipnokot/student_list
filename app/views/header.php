<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/student_list/public/css/bootstrap.min.css"> <!-- Путь к CSS -->
    <title>Сайт абитуриентов</title>
</head>
<body>
    <div class="container">
        <!-- Кнопка "Выйти" -->
        <a class="btn btn-primary mt-4 mb-4" href="index.php?action=logout">Выйти</a>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?= $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
