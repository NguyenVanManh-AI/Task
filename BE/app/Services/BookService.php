<?php

namespace App\Services;

use App\Http\Requests\RequestCreateBook;
use App\Http\Requests\RequestCreateDepartment;
use App\Http\Requests\RequestUpdateBook;
use App\Http\Requests\RequestUpdateDepartment;
use App\Models\Book;
use App\Models\Category;
use App\Repositories\BookInterface;
use App\Repositories\HospitalDepartmentRepository;
use App\Repositories\InforDoctorRepository;
use App\Repositories\InforHospitalRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Faker\Factory as FakerFactory;
use Throwable;

class BookService
{
    protected BookInterface $bookRepository;

    public function __construct(BookInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
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

    public function saveImage(Request $request)
    {
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_book_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/image/thumbnail/books/', $filename);

            return 'storage/image/thumbnail/books/' . $filename;
        }
    }

    public function add(RequestCreateBook $request)
    {
        try {
            $category = Category::find($request->id_category);
            if(empty($category)) return $this->responseError(400, 'Không tìm thấy danh mục !');

            $faker = FakerFactory::create();
            $thumbnail = $this->saveImage($request);
            $data = array_merge(
                $request->all(), 
                [
                    'thumbnail' => $thumbnail,
                    'isbn' => $faker->isbn10,
                    'book_code' => Str::random(10),
                ]
            );
            $book = $this->bookRepository->createBook($data);
            return $this->responseOK(201, $book, 'Thêm mới sách thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function edit(RequestUpdateBook $request, $id)
    {
        try {
            $book = $this->bookRepository->findById($id);
            if ($book) {
                if ($request->hasFile('thumbnail')) {
                    if ($book->thumbnail) {
                        File::delete($book->thumbnail);
                    }
                    $thumbnail = $this->saveImage($request);
                    $data = array_merge($request->all(), ['thumbnail' => $thumbnail]);
                    $department = $this->bookRepository->updateBook($book, $data);
                } else {
                    $request['thumbnail'] = $book->thumbnail;
                    $department = $this->bookRepository->updateBook($book, $request->all());
                }

                return $this->responseOK(200, $department, 'Cập nhật thông tin sách thành công !');
            } else {
                return $this->responseError(400, 'Không tìm thấy sách !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $book = $this->bookRepository->findById($id);
            if ($book) {
                if ($book->thumbnail) File::delete($book->thumbnail);
                $book->delete();
                return $this->responseOK(200, null, 'Xóa sách thành công !');
            } else {
                return $this->responseError(400, 'Không tìm thấy sách !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function deleteMany(Request $request)
    {
        try {

            $list_id = $request->list_id ?? [0];
            $filter = [
                'list_id' => $list_id,
            ];
            $books = $this->bookRepository->searchBook($filter)->get();
            if (count($books) > 0) {
                foreach ($books as $index => $book) {
                    if ($book->book_thumbnail) File::delete($book->book_thumbnail);
                    Book::find($book->book_id)->delete();
                }
            } else {
                return $this->responseError(400, 'Không tìm thấy sách nào !');
            }

            return $this->responseOK(200, null, 'Xóa nhiều sách thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function all(Request $request)
    {
        try {
            $orderBy = $request->typesort ?? 'books.id';
            switch ($orderBy) {
                case 'title':
                    $orderBy = 'books.title';
                    break;

                case 'price':
                    $orderBy = 'books.price';
                    break;

                default:
                    $orderBy = 'books.id';
                    break;
            }

            $orderDirection = $request->sortlatest ?? 'true';
            switch ($orderDirection) {
                case 'true':
                    $orderDirection = 'DESC';
                    break;

                default:
                    $orderDirection = 'ASC';
                    break;
            }

            $filter = (object) [
                'search' => $request->search ?? '',
                'category_id' => $request->category_id ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection
            ];

            if (!(empty($request->paginate))) {
                $books = $this->bookRepository->searchBook($filter)->paginate($request->paginate);
            } else {
                $books = $this->bookRepository->searchBook($filter)->get();
            }

            return $this->responseOK(200, $books, 'Xem tất cả sách thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
