<?php

namespace App\Http\Controllers;

use App\Models\Feriados;
use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeriadoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('doctorservice.feriado.cadastro')
            ->with('estados', Estado::get())
            ->with('meses', $this->getMeses())
            ->with('dias_semana', $this->getDiasSemana())
            ->with('num_semana', $this->getNumSemana());
    }
    public function listar(Request $request)
    {
        $filtro = $request->input('filtro', '');

        if($request->input('chkFeriados')){
            if($request->input('acao') == 'Ativar'){

                foreach($request->input('chkFeriados') as $id){
                    $this->ativar($id);
                }
            }else if($request->input('acao') == 'Inativar'){

                foreach($request->input('chkFeriados') as $id){
                    $this->inativar($id);
                }
            }else{

                foreach($request->input('chkFeriados') as $id){
                    $this->remover($id);
                }
            }
        }

        $feriados = Feriados::select('feriados.id',
                                     'idestado',
                                     'estado.estado',
                                     'nome',
                                     'dia',
                                     'mes',
                                     'ano',
                                     'dia_semana',
                                     'num_semana',
                                     'ativo')
                    ->leftJoin('estado', 'feriados.idestado', 'estado.id')
                    ->where('feriados.ativo', '!=', 'E')
                    ->orderBy('feriados.id', 'asc')
                    ->when($filtro, function($query) use ($filtro){

                        $meses = $this->getMeses();
                        $diaSemana = $this->getDiasSemana();
                        $numSemana = $this->getNumSemana();
                        foreach ($meses as $key => $value) {
                            if ($filtro == $value) {
                                $filtro = $key;
                            }
                        }
                        foreach ($diaSemana as $key => $value) {
                            if ($filtro == $value) {
                                $filtro = $key;
                            }
                        }
                        foreach ($numSemana as $key => $value) {
                            if ($filtro == $value) {
                                $filtro = $key;
                            }
                        }

                        $query->where('estado.estado', 'like', '%' . $filtro . '%');
                        $query->orWhere('feriados.nome', 'like', '%' . $filtro . '%');
                        $query->orWhere('feriados.dia', '=', $filtro);
                        $query->orWhere('feriados.mes', '=', $filtro);
                        $query->orWhere('feriados.ano', '=', $filtro);
                        $query->orWhere('feriados.dia_semana', '=', $filtro);
                        $query->orWhere('feriados.num_semana', '=', $filtro);
                        $query->orWhere('feriados.ativo', '=', $filtro);
                    })
                    ->get();
        
        if (Auth::user()->hasAcesso("Feriado")) {
            
            return view('doctorservice.feriado.pesquisa', [
                'feriados' => $feriados,
                'estados' => Estado::get(),
                'meses' => $this->getMeses(),
                'dias_semana' => $this->getDiasSemana(),
                'num_semana' => $this->getNumSemana(),
                'filtro' => $filtro
            ]);

        } else {
            return redirect('/home')
                    ->with('error', 'Você não tem permissão para acessar a tela de Feriado!');
        }
    }
        
    public function cadastrar(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required|max:100'
        ]);
        $feriados = new Feriados();
        $feriados->nome = $request->nome;
        $feriados->dia = $request->dia;
        $feriados->mes = $request->mes;
        $feriados->ano = $request->ano;
        $feriados->dia_semana = $request->dia_semana;
        $feriados->num_semana = $request->num_semana;
        $feriados->ativo = $request->status;
        $feriados->idestado = $request->uf;
        $feriados->save();
        return redirect()->route('feriado.listar');
    }
    
    public function editar($id)
    {
        $feriados = Feriados::findOrFail($id);
        return view('doctorservice.feriado.editar')
            ->with('estados', Estado::get())
            ->with('feriados', $feriados)
            ->with('meses', $this->getMeses())
            ->with('dias_semana', $this->getDiasSemana())
            ->with('num_semana', $this->getNumSemana());
    }

    public function atualizar(Request $request, $id)
    {
        $this->validate($request, [
            'nome' => 'required|max:100'
        ]);
        if($request->input('remover')){
            $feriados = Feriados::findOrFail($id);
            if ($feriados->ativo != 'E'){
                $feriados->ativo = 'E';
                $feriados->save();
            }
            return redirect()->route('feriado.listar');   
        }
        
        $dados = $request->all();
        $feriados = Feriados::findOrFail($id);
        $feriados->nome = $dados['nome'];
        $feriados->dia = $dados['dia'];
        $feriados->mes = $dados['mes'];
        $feriados->ano = $dados['ano'];
        $feriados->dia_semana = $dados['dia_semana'];
        $feriados->num_semana = $dados['num_semana'];
        $feriados->ativo = $dados['status'];
        $feriados->idestado = $dados['uf'];
        $feriados->save();
        return redirect()->route('feriado.listar');
    }

    private function remover($id)
    {
        $feriados = Feriados::find($id);
        $feriados->ativo = 'E';
        $feriados->save();
    }

    private function inativar($id)
    {
        $feriados = Feriados::find($id);
        $feriados->ativo = 'I';
        $feriados->save();
    }

    private function ativar($id)
    {
        $feriados = Feriados::find($id);
        $feriados->ativo = 'A';
        $feriados->save();
    }

    private function getMeses()
    {
        $meses = [
            '' => '-',
            '1' => 'Janeiro',
            '2' => 'Fevereiro',
            '3' => 'Março',
            '4' => 'Abril',
            '5' => 'Maio',
            '6' => 'Junho',
            '7' => 'Julho',
            '8' => 'Agosto',
            '9' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        ];
        return $meses;

    }

    private function getDiasSemana()
    {
        $dias_semana = [
            '' => '-',
            '1' => 'Domingo',                 
            '2' => 'Segunda',                 
            '3' => 'Terça',                 
            '4' => 'Quarta',                 
            '5' => 'Quinta',                 
            '6' => 'Sexta',                 
            '7' => 'Sábado'
        ];
        return $dias_semana;
    }

    private function getNumSemana()
    {
        $num_semana = [
            '' => '-',
            '1' => 'Primeira',                 
            '2' => 'Segunda',                 
            '3' => 'Terceira',                 
            '4' => 'Quarta',                 
            '5' => 'Quinta' 
        ];
        return $num_semana;
    }
}
