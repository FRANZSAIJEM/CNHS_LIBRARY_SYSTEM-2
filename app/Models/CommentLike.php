<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    use HasFactory;

    protected $table = 'comment_likes';

    protected $fillable = [
        'user_id',
        'comment_id',
    ];

    // Define a relationship to the User model (assuming you have a User model)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define a relationship to the Comment model (assuming you have a Comment model)
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    // Method to check if a user has liked a comment
    public static function hasLikedComment($userId, $commentId)
    {
        return static::where('user_id', $userId)
            ->where('comment_id', $commentId)
            ->exists();
    }
}
