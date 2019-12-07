<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Vaga extends Model
{

    protected $table = 'vaga';

    public function sala()
    {

        return $this->belongsTo(Sala::class, 'idsala');
    }

    public function especialidade()
    {

        return $this->belongsTo(Especialidade::class, 'idespecialidade');
    }


    public function dataInicioFormatada()
    {

        $dataInicio = $this->data_inicio;

        $dataInicioFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataInicio);
        return $dataInicioFormatada->format('d/m/Y');
    }

    public function diaSemanaFormatada()
    {

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        $dataInicio = $this->data_inicio;

        $dataInicioFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataInicio);
        return strftime('%A', strtotime($dataInicioFormatada->timestamp));
    }

    public function dataInicioHoraFormatada()
    {

        $dataInicio = $this->data_inicio;

        $dataInicioFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataInicio);
        return $dataInicioFormatada->format('d/m/Y H:i');
    }

    public function dataInicioSoHoraFormatada()
    {

        $dataInicio = $this->data_inicio;

        $dataInicioFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataInicio);
        return $dataInicioFormatada->format('H:i');
    }

    public function dataFinalFormatada()
    {

        $dataFinal = $this->data_final;

        $dataFinalFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataFinal);
        return $dataFinalFormatada->format('d/m/Y');
    }

    public function dataFinalHoraFormatada()
    {

        $dataFinal = $this->data_final;

        $dataFinalFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataFinal);
        return $dataFinalFormatada->format('d/m/Y H:i');
    }

    public function dataFinalSoHoraFormatada()
    {

        $dataFinal = $this->data_final;

        $dataFinalFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataFinal);
        return $dataFinalFormatada->format('H:i');
    }

    public function cargaHorariaPlantao()
    {

        $dataInicial = $this->data_inicio;
        $dataFinal = $this->data_final;

        $dataInicialFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataInicial);
        $dataFinalFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataFinal);
        return $dataFinalFormatada->diff($dataInicialFormatada)->h;
    }

    public function recorrenciaResultado()
    {
        if($this->recorrencia == 'Q'){

            return 'Quinzenal';
        }elseif ($this->recorrencia == 'S'){

           return 'Semanal';
        }else{

           return 'Mensal';
        }
    }

    public function dataRecorrenciaFim()
    {

        try{

            return Carbon::createFromFormat('Y-m-d H:i:s', $this->recorrencia_fim)->format('d/m/Y');

        }catch (\Exception $e){

            return false;
        }
    }

    public function dataSoHoraRecorrenciaFim()
    {

        try{

            return Carbon::createFromFormat('Y-m-d H:i:s', $this->recorrencia_fim)->format('H:i');

        }catch (\Exception $e){

            return false;
        }
    }

    public function diasTexto()
    {

        $recorrencia = VagaRecorrencia::where(['idvaga' => $this->id])->first();

        $dias = [];

       // dd($recorrencia);

        if($recorrencia->domingo == 1){

            $dias[] = 'dom';
        }

        if($recorrencia->segunda == 1){

            $dias[] = 'seg';
        }

        if($recorrencia->terca == 1){

            $dias[] = 'ter';
        }

        if($recorrencia->quarta == 1){

            $dias[] = 'qua';
        }

        if($recorrencia->quinta == 1){

            $dias[] = 'qui';
        }

        if($recorrencia->sexta == 1){

            $dias[] = 'sex';
        }

        if($recorrencia->sabado == 1){

            $dias[] = 'sab';
        }

        return implode(' - ', $dias);
    }

    public function pagamentoTexto()
    {

        $modalidades = VagaModalidadePagamento::where(['idvaga' => $this->id])->get();

        $arrayModalidade = [];
        if(count($modalidades) > 0){

            foreach ($modalidades as $modalidade){

                $arrayModalidade[] = '<span class="badge badge-secondary">' . $modalidade->modalidade->nome . '</span>';
            }
        }

        return implode(' - ', $arrayModalidade);
    }


    public function visibilidadeResultado()
    {

        if($this->visibilidade == 'P'){

            return 'Público';
        }elseif($this->visibilidade == 'O'){

            return 'Profissionais da Operadora';
        }elseif($this->visibilidade == 'G'){

            return 'Participantes de Grupos';
        }
    }

    public function status()
    {

        return VagaStatus::find($this->idvaga_status)->nome;
    }

    public function medicoEscolhido()
    {

       $candidatura = VagaCandidatura::where([
            'idvaga' => $this->id,
            'idvaga_status' => VagaStatus::where(['nome' => 'Candidato escolhido'])->first()->id
        ])->first();


        $medico = Medico::where([
            'id' => $candidatura->idmedico
        ])->first();


        return PessoaFisica::where([
            'idpessoa' => $medico->idpessoa
        ])->first()->nome;

    }
}