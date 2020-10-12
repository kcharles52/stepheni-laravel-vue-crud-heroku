<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['id', 'user_id', 'forum_id', 'comment'];

    public function user()
    {
      return $this->belongsTo('App\User', 'user_id');
    }
}
