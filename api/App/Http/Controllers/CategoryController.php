<?php

namespace App\Http\Controllers;
class CategoryController
{
    public function index()
    {
        return 'show all categories';
    }

    public function show($id)
    {
        return 'show one category';
    }
}