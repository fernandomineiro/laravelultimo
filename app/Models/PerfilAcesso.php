<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilAcesso extends Model
{
    protected $table = "perfil_acesso";

    public $fillable = [
        'visualizacao',
        'cadastro',
        'edicao',
        'exclusao',
        'idacesso',
        'idperfil',
        'ativo'
    ];

}
