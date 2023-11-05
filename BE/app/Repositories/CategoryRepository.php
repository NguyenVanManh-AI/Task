<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Throwable;

class CategoryRepository extends BaseRepository implements CategoryInterface
{
    public function getModel()
    {
        return Category::class;
    }

    public static function getCategory()
    {
        return (new self)->model;
    }
}
