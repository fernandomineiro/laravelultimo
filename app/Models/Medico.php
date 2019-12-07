<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{

    protected $table = 'medico';

    public $timestamps = false;

    public function especialidadeMedico()
    {

        return $this->hasOne(MedicoEspecialidade::class, 'idmedico');
    }
}