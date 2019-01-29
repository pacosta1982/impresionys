<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subsidio extends Model
{
    protected $table = 'SHMCER';

    protected $primaryKey = 'CerNro';

    public $timestamps = false;

    public $incrementing = false;
    
    
}
