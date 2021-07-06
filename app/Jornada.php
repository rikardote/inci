<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jornada extends Model
{
    protected $fillable = ['jornada'];

    public function employees()
    {
    	return $this->hasMany('App\Employe');
    }


    public function setjornadaAttribute($value)
    {
        $this->attributes['jornada'] = strtoupper($value);
    }

   
   
}