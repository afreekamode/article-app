<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'posts';

    protected $fillable = [
        'body', 
        'subject',
        'tag',
        'media'
   ];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article_comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('post_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
