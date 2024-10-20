<?php

namespace app\models;
use PDO;

class StudentDataGateway {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllStudents($search = '', $order = 'ASC', $limit = 50, $offset = 0)
    {
        // Фильтрация поиска (если применяется)
        $searchQuery = '';
        if (!empty($search)) {
            $searchQuery = "WHERE first_name LIKE :search OR last_name LIKE :search OR group_number LIKE :search";
        }
    
        // SQL-запрос с подстановкой limit и offset
        $sql = "SELECT * FROM applicants $searchQuery ORDER BY exam_score $order LIMIT $limit OFFSET $offset";
        $stmt = $this->pdo->prepare($sql);
    
        // Привязываем параметры для поиска
        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%');
        }
    
        // Выполняем запрос
        $stmt->execute();
    
        // Возвращаем все строки
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTotalStudentsCount($search = '') {
        try {
            $query = 'SELECT COUNT(*) FROM applicants';
            $params = [];
            if (!empty($search)) {
                $query .= ' WHERE first_name LIKE :search OR last_name LIKE :search OR group_number LIKE :search';
                $params[':search'] = '%' . $search . '%';
            }
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return 0; // Если произошла ошибка, возвращаем 0
        }
    }

    public function getStudentById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM applicants WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchObject(Student::class);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return null; // Или выбрасываем исключение
        }
    }

    public function getStudentByEmail($email) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM applicants WHERE email = :email");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchObject(Student::class);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return null; // Или выбрасываем исключение
        }
    }

    public function saveStudent(Student $student) {
        if ($student->id) {
            return $this->updateStudent($student);
        } else {
            return $this->insertStudent($student);
        }
    }

    private function insertStudent(Student $student) {
        $stmt = $this->pdo->prepare("INSERT INTO applicants (first_name, last_name, gender, group_number, email, exam_score, birth_year, is_local) 
            VALUES (:first_name, :last_name, :gender, :group_number, :email, :exam_score, :birth_year, :is_local)");
        
        $stmt->bindValue(':first_name', $student->first_name);
        $stmt->bindValue(':last_name', $student->last_name);
        $stmt->bindValue(':gender', $student->gender);
        $stmt->bindValue(':group_number', $student->group_number);
        $stmt->bindValue(':email', $student->email);
        $stmt->bindValue(':exam_score', $student->exam_score, PDO::PARAM_INT);
        $stmt->bindValue(':birth_year', $student->birth_year, PDO::PARAM_INT);
        $stmt->bindValue(':is_local', $student->is_local, PDO::PARAM_BOOL);
    
        $stmt->execute();
        $student->id = $this->pdo->lastInsertId();
        return $student;
    }

    private function updateStudent(Student $student) {
        $stmt = $this->pdo->prepare("UPDATE applicants SET first_name = :first_name, last_name = :last_name, gender = :gender, 
            group_number = :group_number, email = :email, exam_score = :exam_score, birth_year = :birth_year, is_local = :is_local 
            WHERE id = :id");
    
        $stmt->bindValue(':first_name', $student->first_name);
        $stmt->bindValue(':last_name', $student->last_name);
        $stmt->bindValue(':gender', $student->gender);
        $stmt->bindValue(':group_number', $student->group_number);
        $stmt->bindValue(':email', $student->email);
        $stmt->bindValue(':exam_score', $student->exam_score, PDO::PARAM_INT);
        $stmt->bindValue(':birth_year', $student->birth_year, PDO::PARAM_INT);
        $stmt->bindValue(':is_local', $student->is_local, PDO::PARAM_BOOL);
        $stmt->bindValue(':id', $student->id, PDO::PARAM_INT);
    
        $stmt->execute();
        return $student;
    }

    public function deleteStudent($id) {
        $stmt = $this->pdo->prepare("DELETE FROM applicants WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function searchStudents($search = '', $limit = 50, $offset = 0, $sort = 'exam_score', $order = 'DESC') {
        // Проверяем корректность limit и offset
        $limit = min((int)$limit, 100); // Ограничиваем лимит до 100
        $offset = max((int)$offset, 0);
    
        // Проверяем корректность поля сортировки
        $validSortFields = ['first_name', 'last_name', 'group_number', 'exam_score'];
        $sort = in_array($sort, $validSortFields) ? $sort : 'exam_score';
    
        // Проверяем корректность порядка сортировки
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
    
        $query = "SELECT * FROM applicants";
        $params = [];
    
        // Если есть строка поиска, добавляем её в запрос
        if (!empty($search)) {
            $query .= " WHERE first_name LIKE :search OR last_name LIKE :search OR group_number LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }
    
        // Добавляем сортировку, лимит и смещение
        $query .= " ORDER BY $sort $order LIMIT :limit OFFSET :offset";
    
        $stmt = $this->pdo->prepare($query);
    
        // Привязываем параметры
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
        // Выполняем запрос и возвращаем результат
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Student::class);
    }
}