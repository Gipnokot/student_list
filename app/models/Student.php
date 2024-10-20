<?php

namespace app\models;

class Student
{
    public $id;
    public $first_name;
    public $last_name;
    public $gender;
    public $group_number;
    public $email;
    public $exam_score;
    public $birth_year;
    public $is_local;
    public $created_at;
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';
    const IS_LOCAL_YES = 'yes';
    const IS_LOCAL_NO = 'no';

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
