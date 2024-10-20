<?php

namespace app\services;


use PDO;
use PDOException;
use Dotenv\Dotenv;

class DatabaseService
{
    private $pdo;
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // Путь к .env относительно services

        $dotenv->load();

        $dbHost = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];
        $dbUser = $_ENV['DB_USER'];
        $dbpassword = $_ENV['DB_PASSWORD'];
        $dbPort = $_ENV['DB_PORT'];

        $dsn = "mysql:host=$dbHost;dbname=$dbName;port=$dbPort;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"
        ];

        try {
            $this->pdo = new PDO($dsn, $dbUser, $dbpassword, $options);
        } catch (PDOException $e) {
            echo "Ошибка подключения к базе данных: " . $e->getMessage();
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    public function getConnection()
    {
        return $this->pdo;
    }
}