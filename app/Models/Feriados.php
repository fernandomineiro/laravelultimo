<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feriados extends Model
{
    protected $table = "feriados";

    protected $fillable = [
        'uf', 'nome'
    ];

}
