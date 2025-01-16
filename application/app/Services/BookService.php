<?php

namespace App\Services;

use App\Repositories\BookRepositoryInterface;

class BookService
{
    protected BookRepositoryInterface $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function getAllBooks()
    {
        return $this->bookRepository->all();
    }

    public function createBook(array $data)
    {
        $book = $this->bookRepository->create($data);
        if (isset($data['authors'])) {
            $this->bookRepository->attachAuthors($book->id, $data['authors']);
        }
        return $book;
    }
}
