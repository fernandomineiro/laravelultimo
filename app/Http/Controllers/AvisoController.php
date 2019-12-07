<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Aviso;
use App\Models\Modulo;
use App\Models\Operadora;
use App\Models\OperadoraUnidade;
use App\Models\OperadoraGrupo;
use App\Models\OperadoraGrupoMedico;
use Illuminate\Http\Request;

class AvisoController extends Controller
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
        $modulos = Modulo::all();
        return view('doctorservice.aviso.cadastro')
            ->with('modulos', $modulos);
    }

    public function listar(Request $request)
    {
        $filtro = $request->input('filtro', '');

        if($request->input('chkAviso')){
            if($request->input('acao') == 'Ativar'){

                foreach($request->input('chkAviso') as $id){
                    $this->ativar($id);
                }
            }else if($request->input('acao') == 'Inativar'){

                foreach($request->input('chkAviso') as $id){
                    $this->inativar($id);
                }
            }else{

                foreach($request->input('chkAviso') as $id){
                    $this->remover($id);
                }
            }
        }

        $avisos = Aviso::select('aviso.*',
                               'modulo.nome as modulo',
                               'pessoa_juridica.nome_fantasia as nome_operadora',
                               'operadora_unidade.nome as nome_unidade',
                               'operadora_grupo.nome as nome_grupo_medico')
                    ->leftJoin('modulo', 'aviso.idmodulo', 'modulo.id')
                    ->leftJoin('operadora', 'aviso.idoperadora', 'operadora.id')
                    ->leftJoin('pessoa_juridica', 'operadora.idpessoa', 'pessoa_juridica.idpessoa')
                    ->leftJoin('operadora_unidade', 'aviso.idoperadora_unidade', 'operadora_unidade.id')
                    ->leftJoin('operadora_grupo', 'aviso.idoperadora_grupo_medico', 'operadora_grupo.id')
                    
                    ->where(function ($query){
                        $query->where('aviso.ativo', '<>', 'E');
                    })
                    ->orderBy('id', 'asc')
                    ->when($filtro, function($query) use ($filtro){

                        if($filtro == 'Ativo'){
                            $filtro = 'A';
                        }else if($filtro == 'Inativo'){
                            $filtro = 'I';
                        }

                        $query->where('modulo.nome', 'like', '%' . $filtro . '%');
                        $query->orWhere('pessoa_juridica.nome_fantasia', 'like', '%' . $filtro . '%');
                        $query->orWhere('operadora_unidade.nome', 'like', '%' . $filtro . '%');
                        $query->orWhere('operadora_grupo.nome', 'like', '%' . $filtro . '%');
                        $query->orWhere('aviso.data_hora_abertura', 'like', '%' . $filtro . '%');
                        $query->orWhere('aviso.data_hora_encerramento', 'like', '%' . $filtro . '%');
                        $query->orWhere('aviso.ativo', '=', $filtro);
                    })
                    ->get();

        if(Auth::user()->hasAcesso("Aviso")){

            return view('doctorservice.aviso.pesquisa', [
                'avisos' => $avisos,
            ]);
            
        } else {
            return redirect('/home')
                    ->with('error', 'Você não tem permissão para acessar a tela de Aviso!');
        }
        
    }

    public function getModulos()
    {
        return response()->json(Modulo::all());
    }

    public function getOperadoras()
    {
        $operadoras = Operadora::select('operadora.id', 'pessoa_juridica.nome_fantasia')
            ->join('pessoa_juridica', 'operadora.idpessoa', '=', 'pessoa_juridica.idpessoa')
            ->join('pessoa', 'operadora.idpessoa', '=', 'pessoa.id')
            ->where('pessoa.tipo', 'PJ')
            ->get();

        return response()->json($operadoras);
    }

    public function getTodasUnidadesAjax()
    {
        return response()->json(OperadoraUnidade::select('id', 'nome', 'idoperadora')->get());
    }

    public function getTodosGruposMedicosAjax()
    {
        return response()->json(OperadoraGrupo::select('id', 'nome')->get());
    }

    public function getUnidades($id)
    {
        $operadoraUnidade = OperadoraUnidade::select('id', 'nome')
            ->where('idoperadora', '=', $id)
            ->get();

        return response()->json($operadoraUnidade);
    }

    public function getGrupoMedico($id)
    {
        $operadoraGrupoMedico = OperadoraGrupoMedico::select('operadora_grupo_medico.id', 'operadora_grupo.nome')
            ->join('operadora_grupo', 'operadora_grupo_medico.idoperadora_grupo_medico', 
            '=', 'operadora_grupo.id')
            ->where('operadora_grupo.idoperadora', '=', $id)
            ->get();

        return response()->json($operadoraGrupoMedico);
    }

    public function cadastrar(Request $request)
    {
        $this->validate($request, [
            'url' => 'required|url|max:255',
            'mensagem' => 'required|string|max:255',
            'data_hora_abertura' => 'required|date_format:d/m/Y H:i|before_or_equal:data_hora_encerramento',
            'data_hora_encerramento' => 'required|date_format:d/m/Y H:i'
        ]);
        $aviso = new Aviso();
        $aviso->idmodulo = $request->modulo;
        $aviso->idoperadora = $request->operadora;
        $aviso->idoperadora_unidade = $request->unidade;
        $aviso->idoperadora_grupo_medico = $request->grupo_medico;
        $aviso->mensagem = $request->mensagem;
        $aviso->url = $request->url;
        $aviso->visivel = isset($request->visivel) ? 1 : 0;
        $aviso->data_hora_abertura = date('Y-m-d H:i', strtotime(str_replace("/", "-", $request->data_hora_abertura)));
        $aviso->data_hora_encerramento = date('Y-m-d H:i', strtotime(str_replace("/", "-", $request->data_hora_encerramento)));
        $aviso->ativo = $request->status;
        $aviso->save();
        return redirect()->route('aviso.listar');
    }

    public function editar($id)
    {
        $aviso = Aviso::findOrFail($id);
        $modulos = Modulo::all();
        return view('doctorservice.aviso.editar')
            ->with('aviso', $aviso)
            ->with('modulos', $modulos);
    }

    public function atualizar(Request $request, $id)
    {
        $this->validate($request, [
            'url' => 'required|url|max:255',
            'mensagem' => 'required|string|max:255',
            'data_hora_abertura' => 'required|date_format:d/m/Y H:i|before_or_equal:data_hora_encerramento',
            'data_hora_encerramento' => 'required|date_format:d/m/Y H:i'
        ]);
        if($request->input('remover')){
            $aviso = Aviso::findOrFail($id);
            if ($aviso->ativo != 'E'){
                $aviso->ativo = 'E';
                $aviso->save();
            }
            return redirect()->route('aviso.listar');   
        }
        $aviso = Aviso::findOrFail($id);
        $aviso->idmodulo = $request->modulo;
        $aviso->idoperadora = $request->operadora;
        $aviso->idoperadora_unidade = $request->unidade;
        $aviso->idoperadora_grupo_medico = $request->grupo_medico;
        $aviso->mensagem = $request->mensagem;
        $aviso->url = $request->url;
        $aviso->visivel = isset($request->visivel) ? 1 : 0;
        $aviso->data_hora_abertura = date('Y-m-d H:i', strtotime(str_replace("/", "-", $request->data_hora_abertura)));
        $aviso->data_hora_encerramento = date('Y-m-d H:i', strtotime(str_replace("/", "-", $request->data_hora_encerramento)));
        $aviso->ativo = $request->status;
        $aviso->save();
        return redirect()->route('aviso.listar');
    }

    public function atualizarStatus(Request $request)
    {
        $aviso = new aviso();
        $chkAviso = array();
        $chkAviso = $this->validate($request, [
            'chkAviso' => 'required'
        ]);
        $chkAviso = $request->input('chkAviso');
        switch($request->input('acao')){
            case 'Ativar':
                $aviso = $aviso->find($chkAviso);
                for ($i=0; $i < count($chkAviso); $i++) {    
                    if($aviso[$i]->ativo == 'I'){
                        $aviso[$i]->ativo = 'A';       
                        $aviso[$i]->save();
                    }
                }
                break;
            case 'Inativar':
                $aviso = $aviso->find($chkAviso);
                for ($i=0; $i < count($chkAviso); $i++) {    
                    if($aviso[$i]->ativo == 'A'){
                        $aviso[$i]->ativo = 'I';       
                        $aviso[$i]->save();
                    }
                }
                break;
            case 'Remover':
                $aviso = $aviso->find($chkAviso);
                for ($i=0; $i < count($chkAviso); $i++) {    
                    if($aviso[$i]->ativo != 'E'){
                        $aviso[$i]->ativo = 'E';       
                        $aviso[$i]->save();
                    }
                }
                break;           
        }
        return redirect()->route('aviso.listar');
    }

    private function remover($id)
    {
        $aviso = Aviso::find($id);
        $aviso->ativo = 'E';
        $aviso->save();
    }

    private function inativar($id)
    {
        $aviso = Aviso::find($id);
        $aviso->ativo = 'I';
        $aviso->save();
    }

    private function ativar($id)
    {
        $aviso = Aviso::find($id);
        $aviso->ativo = 'A';
        $aviso->save();
    }
}
