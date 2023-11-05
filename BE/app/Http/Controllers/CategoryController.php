<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCreateCategory;
use App\Http\Requests\RequestUpdateCategory;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function all(Request $request)
    {
        return $this->categoryService->all($request);
    }
}
