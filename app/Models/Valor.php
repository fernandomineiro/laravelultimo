<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Valor extends Model
{

    protected $table = 'valor';

    protected $fillable = [
        'idconvenio', 'idespecialidade', 'idtabela_valor', 'valor_rpa', 'valor_clt', 'valor_pj', 'ativo'
    ];

    public function especialidade()
    {

        return $this->belongsTo(Especialidade::class, 'idespecialidade');
    }

}