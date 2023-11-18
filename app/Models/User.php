<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'grade_level',
        'id_number',
        'contact',
        'email',
        'password',
        'gender',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

        'password' => 'hashed',
        'is_admin' => 'boolean'
    ];

    // User.php
    public function requestedBooks()
    {
        return $this->belongsToMany(Book::class, 'book_requests', 'user_id', 'book_id')
            ->withTimestamps();
    }

    public function hasAcceptedBook()
    {
        return $this->acceptedRequests()->exists();
    }

    // public function hasRequestedBookAny()
    // {
    //     return $this->requestedBooks()->exists();
    // }





    public function hasRequestedBook($bookId)
    {
        return $this->requestedBooks()->where('book_id', $bookId)->exists();
    }





    public function acceptedRequests()
    {
        return $this->hasMany(AcceptedRequest::class);
    }


    public function hasAcceptedRequestForBook($bookId)
    {
        return AcceptedRequest::where('book_id', $bookId)->exists();
    }

    // User.php
    public function hasAcceptedReturnedBookForBook($bookId)
    {
        return $this->acceptedRequests()->where('book_id', $bookId)->where('book_returned', true)->exists();
    }




    public function notifications()
    {
        return $this->belongsToMany(Notification::class)->withTimestamps();
    }

    public function isAdmin()
    {
        return $this->is_admin === 1;
    }

    public function hasLikedComment(Comment $comment)
    {
        // Check if the user has liked the given comment.
        return $this->commentLikes()->where('comment_id', $comment->id)->exists();
    }

    public function commentLikes()
    {
        // Define the relationship between User and CommentLike models.
        return $this->hasMany(CommentLike::class);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function messages()
    {
        return $this->hasMany(Chat::class, 'receiver_id', 'id');
    }

        // Inside your User model (User.php)
    public function hasChatData()
    {
        return $this->messages->count() > 0;
    }



}
