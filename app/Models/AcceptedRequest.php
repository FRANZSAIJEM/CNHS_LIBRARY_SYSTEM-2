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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
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

}
