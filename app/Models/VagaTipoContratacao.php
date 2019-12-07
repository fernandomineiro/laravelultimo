<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class VagaTipoContratacao extends Model
{

    protected $table = 'vaga_tipo_contratacao';

    //public $idvaga;

    public function tipoContratacao(){

        return $this->belongsTo(TipoContratacao::class, 'idtipo_contratacao');
    }

    public function verificarTipoContratacao($tipo){

        $tipoContratacao = VagaTipoContratacao::where(['idvaga' => $this->idvaga, 'idtipo_contratacao' => $tipo])->first();

        if($tipoContratacao){

            return true;
        }
        return false;
    }

    public function tiposTexto()
    {

        $tiposContratacao = VagaTipoContratacao::where(['idvaga' => $this->idvaga])->get();

        $arrayTipos = [];

        foreach ($tiposContratacao as $tipoContratacao){

            $arrayTipos[] = '<span class="badge badge-secondary">' . $tipoContratacao->tipoContratacao->nome . '</span>';
        }

        return implode(' ', $arrayTipos);
    }
}