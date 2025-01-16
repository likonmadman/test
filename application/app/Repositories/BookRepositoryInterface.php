<?php

namespace App\Repositories;

interface BookRepositoryInterface extends BaseRepositoryInterface
{
    public function attachAuthors(int $bookId, array $authorIds);
}
