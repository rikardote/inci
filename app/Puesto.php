<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    protected $table = 'puestos';

    protected $fillable = ['puesto'];

    public function employees()
    {
    	return $this->hasMany('App\Employe');
    }


    public function setpuestoAttribute($value)
    {
        $this->attributes['puesto'] = strtoupper($value);
    }

   
   
}

