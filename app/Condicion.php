<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Condicion extends Model
{
  
    protected $table = 'condiciones';

    protected $fillable = ['condicion'];

    public function employees()
    {
    	return $this->hasMany('App\Employe');
    }
}
