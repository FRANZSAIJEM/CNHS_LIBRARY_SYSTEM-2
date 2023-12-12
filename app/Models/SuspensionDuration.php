<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuspensionDuration extends Model
{
    use HasFactory;

    protected $fillable = ['start_date', 'end_date'];

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
