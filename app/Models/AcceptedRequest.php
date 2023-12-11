<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcceptedRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrower_id',
        'date_borrow',
        'date_pickup',
        'date_return',
        'fines',
        'book_returned',
        'daily_fines',
        'total_fines',
        'late_return'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }


    public function timeDuration()
    {
        return $this->hasOne(TimeDuration::class);
    }


    public function defaultFine()
    {
        return $this->belongsTo(DefaultFine::class, 'default_fine_id');
    }



    // AcceptedRequest.php
    public function isBookReturned()
    {
        return $this->book_returned === 'true'; // Check if it's the string 'true'
    }

    // Additional method to mark the book as returned
    public function markBookAsReturned()
    {
        $this->update([
            'book_returned' => 'true', // Update as the string 'true'
        ]);
    }

    public function userBookRequest()
    {
        return $this->belongsTo(UserBookRequest::class, 'user_book_request_id');
    }

}
