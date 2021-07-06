<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deparment extends Model
{
    protected $table = 'deparments';

    protected $fillable = ['code', 'description'];

    public function employees()
    {
    	return $this->hasMany('App\Employe');
    }

    public function getCodeAttribute($value)
    {
        
       // return $this->code . ' - ' . $this->description;
        return str_pad($value, 5, '0', STR_PAD_LEFT);
    }
    public function setdescriptionAttribute($value)
    {
        $this->attributes['description'] = strtoupper($value);
    }
    public function getDeparmentAttribute($value)
    {
        
       return $this->code . ' - ' . $this->description;
        
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
   
   
}

