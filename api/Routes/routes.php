<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use HM\Core\KC\Application;
use HM\Core\KC\Route;

Route::get('/', [CourseController::class, 'index']);
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/tree', [CategoryController::class, 'tree']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::get('/add-dummy-categories', function () {
    $categories = json_decode(file_get_contents(__DIR__ . "/../Database/Seeds/categories.json"));

    $connection = Application::$app->database->connection;

    try {
        $connection->beginTransaction();

        foreach ($categories as $category) {
            $statement = $connection->prepare('INSERT INTO categories (id, name, parent) VALUES (:id, :name, :parent)');

            $statement->bindParam(':id', $category->id);
            $statement->bindParam(':name', $category->name);
            $statement->bindParam(':parent', $category->parent);

            $statement->execute();
        }

        $connection->commit();

        echo 'Categories Added Successfully';

    } catch (Exception $e) {
        $connection->rollback();
        echo $e->getMessage();
    }
});
Route::get('/add-dummy-courses', function () {
    $courses = json_decode(file_get_contents(__DIR__ . "/../Database/Seeds/courses.json"));

    $connection = Application::$app->database->connection;

    try {
        $connection->beginTransaction();

        foreach ($courses as $course) {
            $statement = $connection->prepare('INSERT INTO courses (course_id, title, description, image_preview, category_id) VALUES (:course_id, :title, :description, :image_preview, :category_id)');

            $statement->bindParam(':course_id', $course->course_id);
            $statement->bindParam(':title', $course->title);
            $statement->bindParam(':description', $course->description);
            $statement->bindParam(':image_preview', $course->image_preview);
            $statement->bindParam(':category_id', $course->category_id);

            $statement->execute();
        }

        $connection->commit();

        echo 'Courses Added Successfully';

    } catch (Exception $e) {
        $connection->rollBack();
        echo $e->getMessage();
    }

});
