<?php

namespace App\Http\Controllers;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function index() :string
    {
        $categoryService = new CategoryService();

        return json_encode($categoryService->getAllCategories());
    }

    public function tree() :string
    {
        $categoryService = new CategoryService();

        return json_encode([
            'html' => $categoryService->getTree(true)
        ]);
    }

    public function show($id) :string
    {
        $categoryService = new CategoryService();

        $category = $categoryService->getCategoryById($id);

        return $category === false ?
            json_encode([
                'success' => false,
                'message' => 'Category not found.'
            ]) :
            json_encode($category);
    }
}