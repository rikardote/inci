<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suplente extends Model
{
    protected $connection = 'mysql-gys';
    protected $table = 'suplentes';

    protected $fillable = ['nombre', 'rfc','beneficiario'];
}
