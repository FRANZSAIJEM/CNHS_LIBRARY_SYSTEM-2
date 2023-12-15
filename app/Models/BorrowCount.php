<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowCount extends Model
{
    use HasFactory;

    protected $fillable = ['count'];

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    // BorrowCount model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
