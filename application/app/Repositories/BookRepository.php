<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository extends BaseRepository implements BookRepositoryInterface
{
    public function __construct(Book $model)
    {
        parent::__construct($model);
    }

    public function attachAuthors(int $bookId, array $authorIds)
    {
        $book = $this->find($bookId);
        $book->authors()->sync($authorIds);
        return $book;
    }
}
