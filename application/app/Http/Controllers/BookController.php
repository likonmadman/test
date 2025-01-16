<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index()
    {
        return response()->json($this->bookService->getAllBooks());
    }

    public function store(StoreBookRequest $request)
    {
        $data = $request->validated();

        $book = $this->bookService->createBook($data);
        return response()->json($book, 201);
    }
}
