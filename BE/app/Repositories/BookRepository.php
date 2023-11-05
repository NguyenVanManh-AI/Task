<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Throwable;

class BookRepository extends BaseRepository implements BookInterface
{
    public function getModel()
    {
        return Book::class;
    }

    public static function findById($id)
    {
        return (new self)->model->find($id);
    }

    public static function createBook($data)
    {
        DB::beginTransaction();
        try {
            $newBook = (new self)->model->create($data);
            DB::commit();

            return $newBook;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function updateBook($result, $data)
    {
        DB::beginTransaction();
        try {
            $result->update($data);
            DB::commit();

            return $result;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function searchBook($filter)
    {
        $filter = (object) $filter;
        $query = (new self)->model->selectRaw('
            books.*,
            categories.*,
            books.id AS book_id, 
            books.thumbnail AS book_thumbnail,
            categories.id AS category_id, 
            categories.thumbnail AS category_thumbnail
        ');

        $query->leftJoin('categories', 'categories.id', '=', 'books.id_category');

        $query->when(!empty($filter->list_id), function ($query) use ($filter) {
            $query->whereIn('books.id', $filter->list_id);
        });

        $query->when(!empty($filter->book_id), function ($query) use ($filter) {
            $query->where('books.id', $filter->book_id);
        });

        $query->when(!empty($filter->category_id), function ($query) use ($filter) {
            $query->where('categories.id', '=', $filter->category_id);
        });

        $query->when(!empty($filter->search), function ($query) use ($filter) {
            $query->where(function ($query) use ($filter) {
                $query->where('books.title', 'LIKE', '%' . $filter->search . '%')
                    ->orWhere('books.author', 'LIKE', '%' . $filter->search . '%')
                    ->orWhere('books.publisher', 'LIKE', '%' . $filter->search . '%')
                    ->orWhere('books.publication_year', 'LIKE', '%' . $filter->search . '%');
            });
        });

        $query->when(!empty($filter->orderBy), function ($query) use ($filter) {
            $query->orderBy($filter->orderBy, $filter->orderDirection);
        });

        return $query;
    }
}
