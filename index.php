<?php

// Подключаем необходимые файлы и классы
require_once __DIR__ . '/vendor/autoload.php';

use app\controllers\StudentController;
use app\models\StudentDataGateway;
use app\services\StudentValidator;

// Инициализируем сессию
session_start();

// Инициализируем подключение к базе данных
$pdo = new PDO('mysql:host=localhost;dbname=student_list', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Создаем объект gateway (для работы с данными)
$gateway = new StudentDataGateway($pdo);

// Создаем объект валидатора
$validator = new StudentValidator();

// Создаем объект контроллера, передавая gateway и validator
$StudentController = new StudentController($gateway, $validator);

// Получаем действие
$action = $_GET['action'] ?? 'list';

// Проверяем, авторизован ли пользователь
if (isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])) {
    // Если пользователь авторизован, показываем страницу редактирования
    $StudentController->editAction();
} else {
    // Если не авторизован, проверяем запрашиваемое действие
    switch ($action) {
        case 'list':
            $data = $StudentController->listAction();
            break;
        case 'search':
            $data = $StudentController->searchAction($_GET['search']);
            break;
        case 'register':
            $StudentController->registerAction();
            break;
        case 'logout':
            $StudentController->logoutAction();
            break;
        case 'delete':
            $StudentController->deleteAction($_GET['id']);
            break;
        default:
            // Если действие не найдено, показываем 404 ошибку
            header('HTTP/1.1 404 Not Found');
            echo '404 Not Found';
            exit;
    }

    // Отображаем один раз таблицу, независимо от того, идет ли поиск или отображение полного списка
    include __DIR__ . '/app/views/layout.php';
}