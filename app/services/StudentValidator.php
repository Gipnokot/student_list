<?php

namespace app\services;

use app\models\Student;
use app\models\StudentDataGateway;

class StudentValidator {
    public function validate(Student $student, StudentDataGateway $gateway) {
        $errors = [];

        if (empty($student->first_name)) {
            $errors['first_name'] = 'Имя обязательно для заполнения';
        }

        if (empty($student->last_name)) {
            $errors['last_name'] = 'Фамилия обязательна для заполнения';
        }

        if (empty($student->email) || !filter_var($student->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Неверный формат email';
        } elseif (!$student->id && $gateway->getStudentByEmail($student->email)) {
            $errors['email'] = 'Email уже занят';
        }

        if (empty($student->exam_score) || $student->exam_score < 0 || $student->exam_score > 300) {
            $errors['exam_score'] = 'Неверное количество баллов';
        }

        if (empty($student->birth_year) || $student->birth_year < 1900 || $student->birth_year > date('Y')) {
            $errors['birth_year'] = 'Неверный год рождения';
        }

        return $errors;
    }
}