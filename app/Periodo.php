<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $table = 'periodos';

    protected $fillable = ['periodo', 'year'];

    public function getPeriodooAttribute()
    {
       return $this->periodo . '/' . $this->year;
    }
    public function incidencia()
    {
        return $this->hasMany('App\Incidencia');
    }

    public function getPeriodoAttribute($value)
    {
        return str_pad($value, 2, '0', STR_PAD_LEFT);
    }
}
