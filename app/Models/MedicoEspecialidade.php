<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MedicoEspecialidade extends Model
{

    protected $table = 'medico_especialidade';

    public $timestamps = false;

    public function especialidade()
    {

        return $this->belongsTo(Especialidade::class, 'idespecialidade');
    }
}