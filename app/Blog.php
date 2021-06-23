<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $fillable = [
        'title',
        'img',
        'description',
    ];

    public function comments(){
        return $this->hasMany('App\Blog_Comment' ,'blog_id');
    }
}
