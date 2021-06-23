<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors';
    protected $fillable = [
      'name' ,
      'job' ,
      'img' ,
      'description' ,
    ];
}
