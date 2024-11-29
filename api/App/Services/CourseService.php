<?php

namespace App\Services;

use HM\Core\KC\Application;
use PDO;
use stdClass;

class CourseService
{
    public function getAllCourses($params = []): array
    {
        $SQL = 'select courses.id, courses.course_id, courses.title as name, courses.description, courses.image_preview as preview, categories.name as main_category_name from courses inner join categories on courses.category_id = categories.id';

        $hasCategoryInQueryString = isset($params['category_id']) && $params['category_id'];
        $SQL .= $hasCategoryInQueryString ? ' where courses.category_id = :category_id' : '';

        $result = Application::$app
            ->database
            ->connection
            ->prepare($SQL);

        if ($hasCategoryInQueryString) {
            $result->bindValue(':category_id', $params['category_id'], PDO::PARAM_STR);
        }

        $result->execute();

        return $result->fetchAll(PDO::FETCH_OBJ);
    }
    public function getCourseById($id): bool|StdClass
    {
        $result = Application::$app
            ->database
            ->connection
            ->prepare('select courses.id, courses.course_id, courses.title as name, courses.description, courses.image_preview as preview, categories.name as main_category_name from courses inner join categories on courses.category_id = categories.id where courses.course_id = :course_id');

        $result->bindParam(':course_id', $id);
        $result->execute();

        return $result->fetch(PDO::FETCH_OBJ);
    }
}