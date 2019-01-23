<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subsidio extends Model
{
    protected $table = 'SHMCER';
    
    protected $fillable = ['CerposNom'];
}
