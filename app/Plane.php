<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plane extends Model
{
    protected $table = 'plans';
    protected $fillable = [
      'title',
      'price',
    ];

    public function sections(){
        return $this -> hasMany('App\Section' , 'plan_id');
    }
}
