<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = ['horario'];

    public function employees()
    {
    	return $this->belongsTo('App\Employe');
    }

}
