<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qna extends Model
{
    protected $table = 'qnas';

    protected $fillable = ['qna', 'year', 'description'];

    public function setdescriptionAttribute($value)
    {
        $this->attributes['description'] = strtoupper($value);
    }
    public function getQnaaAttribute()
    {
       return $this->qna . '/' . $this->year . ' - ' .$this->description;
    }

    public function incidencia()
    {
        return $this->hasMany('App\Incidencia');
    }

    public function getQnaAttribute($value)
    {
        return str_pad($value, 2, '0', STR_PAD_LEFT);
    }
}
