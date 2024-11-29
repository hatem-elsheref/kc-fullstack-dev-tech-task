<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use HM\Core\KC\Application;

class CourseController extends Controller
{
    public function index(): string
    {
        $courseService = new CourseService();

        $queryString = Application::$app->request->query();

        return json_encode($courseService->getAllCourses($queryString));
    }

    public function show($id): string
    {
        $courseService = new CourseService();

        $course = $courseService->getCourseById($id);

        return $course === false ?
            json_encode([
                'success' => false,
                'message' => 'Course not found.'
            ]) :
            json_encode($course);
    }
}
