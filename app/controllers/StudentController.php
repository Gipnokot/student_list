<?php

namespace app\controllers;

use app\models\Student;
use app\models\StudentDataGateway;
use app\services\StudentValidator;

class StudentController {
    protected $gateway;
    protected $validator;

    public function __construct(StudentDataGateway $gateway, StudentValidator $validator) {
        $this->gateway = $gateway;
        $this->validator = $validator;
    }

    private function setStudentDataFromRequest(Student $student) {
        $student->first_name = trim($_POST['first_name']);
        $student->last_name = trim($_POST['last_name']);
        $student->gender = $_POST['gender'];
        $student->group_number = trim($_POST['group_number']);
        $student->email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $student->exam_score = (int)$_POST['exam_score'];
        $student->birth_year = (int)$_POST['birth_year'];
        $student->is_local = isset($_POST['is_local']) ? (bool)$_POST['is_local'] : false;
    }

    public function listAction() {
        $search = $_GET['search'] ?? '';
        $order = $_GET['order'] ?? 'DESC';
        $currentPage = $_GET['page'] ?? 1;
        $limit = 50;
        $offset = ($currentPage - 1) * $limit;

        $students = $this->gateway->getAllStudents($search, $order, $limit, $offset);
        $totalStudents = $this->gateway->getTotalStudentsCount($search);

        return compact('students', 'search', 'order', 'totalStudents', 'currentPage');
    }

    public function searchAction($search) {
        $currentPage = $_GET['page'] ?? 1;
        $limit = 50;
        $offset = ($currentPage - 1) * $limit;

        $students = $this->gateway->searchStudents($search, $limit, $offset);
        $totalStudents = $this->gateway->getTotalStudentsCount($search);

        return compact('students', 'search', 'totalStudents', 'currentPage');
    }

    public function registerAction() {
        $student = new Student();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->setStudentDataFromRequest($student);
                $errors = $this->validator->validate($student, $this->gateway);

                if (empty($errors)) {
                    $student = $this->gateway->saveStudent($student);
                    $_SESSION['user_id'] = $student->id;
                    setcookie('user_id', $student->id, time() + (86400 * 3650), '/'); // 10 лет

                    $_SESSION['success_message'] = 'Спасибо, данные сохранены, вы можете при желании их отредактировать.';
                    header('Location: index.php?action=list', true, 303);
                    exit;
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $errors[] = 'Произошла ошибка при регистрации. Пожалуйста, попробуйте позже.';
            }
        }

        include __DIR__ . '/../views/form.php';
    }

    public function editAction() {
        if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
            header('Location: index.php', true, 303);
            exit;
        }

        $userId = $_SESSION['user_id'] ?? $_COOKIE['user_id'];
        $student = $this->gateway->getStudentById($userId);
        $errors = [];

        if ($student === null) {
            $_SESSION['error_message'] = 'Студент не найден.';
            header('Location: index.php?action=list', true, 303);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->setStudentDataFromRequest($student);
                $errors = $this->validator->validate($student, $this->gateway);

                if (empty($errors)) {
                    $this->gateway->saveStudent($student);
                    $_SESSION['success_message'] = 'Изменения сохранены успешно!';
                    header('Location: index.php?action=list', true, 303);
                    exit;
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $errors[] = 'Произошла ошибка при сохранении изменений. Пожалуйста, попробуйте позже.';
            }
        }

        include __DIR__ . '/../views/form.php';
    }

    public function logoutAction() {
        session_unset();
        session_destroy();
        setcookie('user_id', '', time() - 3600, '/');

        $_SESSION['success_message'] = 'Вы успешно вышли из системы!';
        header('Location: index.php', true, 303);
        exit;
    }

    public function deleteAction($id) {
        try {
            $this->gateway->deleteStudent($id);
            $_SESSION['success_message'] = 'Студент удалён успешно!';
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = 'Произошла ошибка при удалении студента.';
        }
        header('Location: index.php?action=list', true, 303);
        exit;
    }
}