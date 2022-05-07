<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'post_comments';
    
    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function comments_reply()
    {
        return $this->hasMany(Comment::class, 'article_id');
    }
}
