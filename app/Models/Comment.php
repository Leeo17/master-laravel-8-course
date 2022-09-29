<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = ['user_id', 'content'];

  public function commentable()
  {
    return $this->morphTo();
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public static function boot()
  {
    parent::boot();

    static::creating(function (Comment $comment) {
      if ($comment->commentable_type === BlogPost::class) {
        Cache::tags(['blog-post'])->forget("blog-post-{$comment->commentable_id}");
        Cache::tags(['blog-post'])->forget("blog-post-most-commented");
      }
    });
  }
}
