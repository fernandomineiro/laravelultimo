<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PessoaFisica extends Model
{
    protected $table = "pessoa_fisica";

    public $timestamps = false;

    protected $fillable = [
        'nome', 'estado_civil', 'cpf', 'sexo', 'data_nascimento', 'ativo'
    ];
}
