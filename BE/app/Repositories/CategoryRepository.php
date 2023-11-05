<?php

namespace App\Repositories;

use App\Models\Category;

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
