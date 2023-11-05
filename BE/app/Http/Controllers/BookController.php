<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCreateBook;
use App\Http\Requests\RequestUpdateBook;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BookController extends Controller
{
    protected BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function add(RequestCreateBook $request)
    {
        return $this->bookService->add($request);
    }

    public function edit(RequestUpdateBook $request, $id)
    {
        return $this->bookService->edit($request, $id);
    }

    public function delete($id)
    {
        return $this->bookService->delete($id);
    }

    public function deleteMany(Request $request)
    {
        return $this->bookService->deleteMany($request);
    }

    public function all(Request $request)
    {
        return $this->bookService->all($request);
    }
}
