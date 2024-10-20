<?php
$pdo = new PDO('mysql:host=localhost;dbname=student_list', 'root', ''); // Измените на свои данные

// Подготовьте SQL запрос
$sql = "DELETE FROM applicants"; // Замените 'students' на имя вашей таблицы
$stmt = $pdo->prepare($sql);

// Выполните запрос
if ($stmt->execute()) {
    echo "Все записи успешно удалены.";
} else {
    echo "Ошибка при удалении записей.";
}
