<?php

namespace App\Repositories;

interface BookInterface extends RepositoryInterface
{
    public static function findById($id);

    public static function createBook($data);

    public static function updateBook($result, $data);

    public static function searchBook($filter);
}
