<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class returnedBook extends Model
{
    use HasFactory;

    protected $table = 'returned_books';

    protected $fillable = [
        'borrower_id',
        'notification_text',
        'created_at',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }
}
