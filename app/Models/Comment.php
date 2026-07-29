<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'user_name', 'body', 'parent_id', 'is_approved'];

    public function post()
    {
        return $table = $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->where('is_approved', true)->latest();
    }


    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}