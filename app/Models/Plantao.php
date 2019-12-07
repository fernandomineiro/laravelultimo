<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Plantao extends Model
{
    protected $table = 'plantao';
    protected $fillable = ['idmedico'];
    
    public function plantaoStatus()
    {
        return $this->belongsTo(PlantaoStatus::class, 'idplantao_status', 'id');
    }

    public function tipoContratacao()
    {
        return $this->belongsTo(TipoContratacao::class, 'idtipo_contratacao', 'id');
    }    

    public function plantaoAtendimento()
    {
        return $this->hasOne(PlantaoAtendimento::class, 'idplantao', 'id');
    }

    public function diaSemanaFormatada()
    {

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        $dataInicio = $this->data_inicio;

        return strftime('%A', strtotime($dataInicio));
    }

    public function dataHoraInicioFormatada()
    {

        $dataInicio = $this->data_inicio;

        $dataHoraInicioFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataInicio);
        return $dataHoraInicioFormatada->format('d/m/Y H:i');
    }

    public function dataHoraTerminoFormatada()
    {

        $dataTermino = $this->data_termino;

        $dataHoraTerminoFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataTermino);
        return $dataHoraTerminoFormatada->format('d/m/Y H:i');
    }

    public function dataInicioFormatada()
    {

        $dataInicio = $this->data_inicio;

        $dataInicioFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataInicio);
        return $dataInicioFormatada->format('d/m/Y');
    }

    public function dataTerminoFormatada()
    {

        $dataTermino = $this->data_termino;

        $dataTerminoFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataTermino);
        return $dataTerminoFormatada->format('d/m/Y');
    }

    public function dataInicioSoHoraFormatada()
    {

        $dataInicio = $this->data_inicio;

        $dataInicioFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataInicio);
        return $dataInicioFormatada->format('H:i');
    }

    public function dataTerminoSoHoraFormatada()
    {

        $dataTermino = $this->data_termino;

        $dataTerminoFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataTermino);
        return $dataTerminoFormatada->format('H:i');
    }

    public function checkIn()
    {
        if ($this->check_in != "") {
            return Carbon::createFromFormat('Y-m-d H:i:s', $this->check_in)->format('d/m/Y H:i');
        }
        
        return "";
    }

    public function checkOut()
    {
        if ($this->check_out != "") {
            return Carbon::createFromFormat('Y-m-d H:i:s', $this->check_out)->format('d/m/Y H:i');
        }
        
        return "";
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

    public function cargaHorariaPlantao()
    {

        $dataInicial = $this->data_inicio;
        $dataTermino = $this->data_termino;

        $dataInicialFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataInicial);
        $dataTerminoFormatada = Carbon::createFromFormat('Y-m-d H:i:s', $dataTermino);
        return $dataTerminoFormatada->diff($dataInicialFormatada)->h;
    }

    public function horasRealizadas()
    {
        $checkIn = $this->check_in;
        $checkout = $this->check_out;

        if($checkIn != "" && $checkout != ""){
            $checkInFormatado = Carbon::createFromFormat('Y-m-d H:i:s', $checkIn);
            $checkOutFormatado = Carbon::createFromFormat('Y-m-d H:i:s', $checkout);
            $horasRealizadas = $checkOutFormatado->diff($checkInFormatado)->h;

            Plantao::where(['id' => $this->id])->update(['hora_realizada' => $horasRealizadas]);

            return $horasRealizadas;   
        } else {
            return "";
        }

    }
}