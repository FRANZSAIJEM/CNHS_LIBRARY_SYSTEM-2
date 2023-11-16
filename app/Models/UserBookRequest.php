<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBookRequest extends Model
{
    use HasFactory;

    protected $table = 'user_book_requests';

    protected $fillable = [
        'user_id',
        'request_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
