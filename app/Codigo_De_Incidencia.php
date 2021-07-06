<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Codigo_De_Incidencia extends Model
{
    protected $table = 'codigos_de_incidencias';

    protected $fillable = ['code', 'description', 'grupo'];

    public function setdescriptionAttribute($value)
    {
        $this->attributes['description'] = strtoupper($value);
    }

    public function getCodigoAttribute()
    {
        return $this->code . ' - ' . $this->description;
    }
    public function incidencia()
    {
        return $this->hasMany('App\Incidencia');
    }
        public function getCodeAttribute($value)
    {
        return str_pad($value, 2, '0', STR_PAD_LEFT);
    }
}
