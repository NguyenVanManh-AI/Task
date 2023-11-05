<?php

namespace App\Services;

use App\Repositories\CategoryInterface;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Throwable;

class CategoryService
{
    protected CategoryInterface $categoryRepository;

    public function __construct(
        CategoryInterface $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    public function responseOK($status = 200, $data = null, $message = '')
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ], $status);
    }

    public function responseError($status = 400, $message = '')
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
        ], $status);
    }

    public function all(Request $request)
    {
        try {
            $categorys = CategoryRepository::getCategory()->get();

            return $this->responseOK(200, $categorys, 'Xem tất cả danh mục thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
