<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plantao;
use App\Models\Sala;
use App\Models\Vaga;
use Carbon\Carbon;

class PlantaoController extends Controller
{
    public function buscarEscalas()
    {
        $escalas = Vaga::select('id as vaga_id')->get();
        return response()->json($escalas);
    }

    public function buscarStatus()
    {
        $status = Plantao::select('plantao_status.nome')
            ->join('plantao_status', 'plantao.idplantao_status', 'plantao_status.id')
            ->distinct()
            ->get();

        return response()->json($status);
    }

    public function buscarSalas()
    {
        $salas = Vaga::select('sala.nome')->join('sala', 'vaga.idsala', 'sala.id')->get();        
        return response()->json($salas);
    }

    public function  buscarUnidades()
    {
        $unidades = Vaga::select('operadora_unidade.nome as unidade')
            ->join('sala', 'vaga.idsala', 'sala.id')
            ->join('operadora_unidade', 'sala.idoperadora_unidade', 'operadora_unidade.id')
            ->get();
        return response()->json($unidades);
    }

    public function buscarRecorrencias()
    {
        $recorrencias = Vaga::select('recorrencia')->distinct()->get();
        return response()->json($recorrencias);
    }

    public function buscarEspecialidades()
    {
        $especialidades = Vaga::select('especialidade.nome as especialidade')
            ->join('especialidade', 'vaga.idespecialidade', 'especialidade.id')
            ->distinct()
            ->get();
        return response()->json($especialidades);
    }
    
    public function buscarMedicos()
    {
        $medicos = Plantao::select('pessoa_fisica.nome as medico')
            ->join('medico', 'plantao.idmedico', 'medico.id')
            ->join('pessoa', 'medico.idpessoa', 'pessoa.id')
            ->join('pessoa_fisica', 'pessoa.id', 'pessoa_fisica.idpessoa')
            ->distinct()
            ->get();
        return response()->json($medicos);
    }

    public function buscarDatasEHorasPlantoes(){
        $datasEHorasPlantoes = Plantao::select(
            'plantao.data_inicio',
            'plantao.data_termino'
        )
        ->join('vaga', 'plantao.idvaga', 'vaga.id')
        ->get();

        return response()->json($datasEHorasPlantoes);
    }

    public function listar(Request $request)
    {
        $str = $request->input('filtro', '');
        $filtro = strtolower($str);
        
        if ($request->input('chkPlantao')) {
            if ($request->input('acao') == 'Ativar') {

                foreach ($request->input('chkPlantao') as $id) {
                    $this->ativar($id);
                }
            } else if ($request->input('acao') == 'Inativar') {

                foreach ($request->input('chkPlantao') as $id) {
                    $this->inativar($id);
                }
            } else {

                foreach ($request->input('chkPlantao') as $id) {

                    $this->remover($id);
                }
            }
        }

        $plantoes = Plantao::select(
                        'vaga.id as vaga_id',
                        'plantao.id',
                        'plantao_status.nome as status',
                        'operadora_unidade.nome as unidade',
                        'sala.nome as sala',
                        'plantao.data_inicio',
                        'plantao.data_termino',
                        'vaga.recorrencia',
                        'plantao.hora_planejada',
                        'especialidade.nome as especialidade',
                        'pessoa_fisica.nome as medico'
                    )
                    ->join('vaga', 'plantao.idvaga', 'vaga.id')
                    ->join('sala', 'vaga.idsala', 'sala.id')
                    ->join('especialidade', 'vaga.idespecialidade', 'especialidade.id')
                    ->join('operadora_unidade', 'sala.idoperadora_unidade', 'operadora_unidade.id')
                    ->join('medico', 'plantao.idmedico', 'medico.id')
                    ->join('pessoa', 'medico.idpessoa', 'pessoa.id')
                    ->join('pessoa_fisica', 'pessoa.id', 'pessoa_fisica.idpessoa')
                    ->join('plantao_status', 'plantao.idplantao_status', 'plantao_status.id')
                    ->orderBy('plantao.id', 'asc')
                    ->when($filtro, function ($query) use ($filtro)
                    {
                        if ($filtro == 'ativo') {
                            $filtro = 'A';
                        } else if ($filtro == 'inativo') {
                            $filtro = 'I';
                        } else if ($filtro == 'quinzenal' || $filtro == 'quinzena') {
                            $filtro = 'Q';
                        } else if ($filtro == 'mensal' || $filtro == 'mês') {
                            $filtro = 'M';
                        } else if ($filtro == 'semanal' || $filtro == 'semana') {
                            $filtro = 'S';
                        }
                        $query->where(function($query) use ($filtro){
                            $query->orWhere('operadora_unidade.nome', 'like', '%' . $filtro . '%');
                            $query->orWhere('sala.nome', 'like', '%' . $filtro . '%');
                            $query->orWhereDate('plantao.data_inicio', date('Y-m-d', strtotime(str_replace("/", "-", $filtro))));
                            $query->orWhereDate('plantao.data_termino', date('Y-m-d', strtotime(str_replace("/", "-", $filtro))));
                            $query->orWhereDay('plantao.data_inicio', $filtro);
                            $query->orWhereDay('plantao.data_termino', $filtro);
                            $query->orWhereMonth('plantao.data_inicio', $filtro);
                            $query->orWhereMonth('plantao.data_termino', $filtro);
                            $query->orWhereYear('plantao.data_inicio', $filtro);
                            $query->orWhereYear('plantao.data_termino', $filtro);
                            $query->orWhereTime('plantao.data_inicio', $filtro);
                            $query->orWhereTime('plantao.data_termino', $filtro);
                            $query->orWhere('vaga.recorrencia', $filtro);
                            $query->orWhere('especialidade.nome', 'like', '%' . $filtro . '%');
                            $query->orWhere('pessoa_fisica.nome', 'like', '%' . $filtro . '%');
                            $query->orWhere('plantao_status.nome', 'like', '%' . $filtro . '%');
                        });
                    })
                    ->paginate(10);

        return view('plantao.pesquisa', ['plantoes' => $plantoes]);
    }

}
