<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'SHMGNU';

    protected $primaryKey = ['NucCod', 'GnuCod'];

    public $incrementing = false;
    
    
}
