<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog_Comment extends Model
{
    protected $table = 'blog_comments';
    protected $fillable = [
      'blog_id',
      'user_id',
      'comment',
    ];

    public function blog(){
        return $this->belongsTo('App\Blog' ,'blog_id');
    }
}
