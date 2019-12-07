<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class VagaModalidadePagamento extends Model
{

    protected $table = 'vaga_modalidade_pagamento';


    public function modalidade()
    {

        return $this->belongsTo(ModalidadePagamento::class, 'idmodalidade_pagamento');
    }

    public function verificarModalidade($tipo){

        $modalidade = VagaModalidadePagamento::where(['idvaga' => $this->idvaga, 'idmodalidade_pagamento' => $tipo])->first();

        if($modalidade){

            return true;
        }
        return false;
    }
}