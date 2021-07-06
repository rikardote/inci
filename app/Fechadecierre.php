<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fechadecierre extends Model
{
	protected $table = 'Fechadecierre';
  	protected $fillable = ['fecha_cierre']; 
}
