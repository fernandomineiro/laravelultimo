<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SalaEspecialidade extends Model
{

    protected $table = 'sala_especialidade';


    public function especialidade()
    {

        return $this->belongsTo(Especialidade::class, 'idespecialidade');
    }
}