<?php

require_once '../vendor/autoload.php';

use app\services\DatabaseService;
use Faker\Factory;

// Подключаемся к базе данных
$databaseService = new DatabaseService();
$pdo = $databaseService->getConnection();

// Создаем экземпляр Faker
$faker = Factory::create();

// Получаем текущий год
$currentYear = date('Y');
$birthYear = $currentYear - 16; // Год рождения 16 лет назад

// Функция для генерации номера группы, содержащего как минимум одну букву и одну цифру
function generateGroupNumber($faker) {
    do {
        // Генерируем строку, состоящую из букв и цифр
        $letters = $faker->lexify(str_repeat('?', rand(1, 4))); // Случайные буквы (от 1 до 4)
        $digits = $faker->numerify(str_repeat('#', rand(1, 4))); // Случайные цифры (от 1 до 4)

        // Объединяем буквы и цифры в одну строку
        $groupNumber = $letters . $digits;

        // Перемешиваем символы в строке, чтобы гарантировать случайный порядок букв и цифр
        $groupNumber = str_shuffle($groupNumber);

        // Ограничиваем длину группы 5 символами (если больше)
        $groupNumber = substr($groupNumber, 0, 5);

        // Проверяем, что в строке есть хотя бы одна буква и одна цифра
        $hasLetter = preg_match('/[a-zA-Z]/', $groupNumber);
        $hasDigit = preg_match('/\d/', $groupNumber);
    } while (!$hasLetter || !$hasDigit); // Повторяем генерацию, если не соблюдены условия

    return $groupNumber;
}

// Генерируем и вставляем 100 фейковых записей
for ($i = 0; $i < 100; $i++) {
    $stmt = $pdo->prepare("INSERT INTO applicants (first_name, last_name, gender, group_number, email, exam_score, birth_year, is_local) VALUES (:first_name, :last_name, :gender, :group_number, :email, :exam_score, :birth_year, :is_local)");

    $stmt->execute([
        ':first_name' => $faker->firstName,
        ':last_name' => $faker->lastName,
        ':gender' => $faker->randomElement(['male', 'female']),
        ':group_number' => generateGroupNumber($faker), // Используем сгенерированный номер группы
        ':email' => $faker->unique()->safeEmail,
        ':exam_score' => $faker->numberBetween(0, 400), // Оценка от 0 до 400
        ':birth_year' => $birthYear, // Год рождения 16 лет назад
        ':is_local' => $faker->randomElement(['local', 'non_local']),
    ]);
}

echo "Фейковые данные успешно добавлены в таблицу applicants.";
