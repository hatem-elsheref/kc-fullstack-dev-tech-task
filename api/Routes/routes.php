<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use HM\Core\KC\Route;

Route::get('/'                , [CourseController::class, 'index']);
Route::get('/courses'         , [CourseController::class, 'index']);
Route::get('/courses/{id}'    , [CourseController::class, 'show']);

Route::get('/categories'      , [CategoryController::class, 'index']);
Route::get('/categories/{id}' , [CategoryController::class, 'show']);
