<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnedBookNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'returnedBook_id',

    ];


    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function returnedBook()
    {
        return $this->belongsTo(returnedBook::class, 'returnedBook_id');
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
