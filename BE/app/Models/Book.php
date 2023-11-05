<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'isbn',
        'book_code',
        'id_category',
        'title',
        'author',
        'publication_year',
        'publisher',
        'price',
        'thumbnail',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
