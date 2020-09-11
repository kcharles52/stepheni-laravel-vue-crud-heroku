<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
  protected $fillable = ['id', 'user_id', 'title', 'content', 'likes', 'comments', 'created_at', 'updated_at'];

  public function commentModel()
  {
    return $this->hasMany('App\Comment', 'forum_id');
  }
}
