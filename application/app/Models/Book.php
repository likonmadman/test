<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'publisher_id'];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_book');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }
}
