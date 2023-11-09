<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultFine extends Model
{
    use HasFactory;
    protected $fillable = ['amount', 'description', 'total_fines', 'set_daily_fines'];
}
