<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'subject',
        'availability',
        'isbn',
        'description',
        'image',
    ];

    public function requestedByUsers()
{
    return $this->belongsToMany(User::class, 'book_requests', 'book_id', 'user_id')
        ->withTimestamps();
}


public function acceptedRequests()
{
    return $this->hasMany(AcceptedRequest::class);
}


}
