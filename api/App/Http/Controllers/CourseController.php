<?php

namespace App\Http\Controllers;


class CourseController extends Controller
{
    public function index()
    {
        return 'show all courses';
    }

    public function show($id)
    {
        return 'show one course ' . $id;
    }
}