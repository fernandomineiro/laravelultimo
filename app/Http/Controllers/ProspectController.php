<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Prospect;
use App\Models\StatusProspect;
use App\Models\Comunicacao;
use App\Models\ComunicacaoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProspectController extends Controller
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
        $status = StatusProspect::select('id', 'nome')->get();
        return view('doctorservice.prospect.cadastro')
            ->with('status', $status);
    }

    public function listar(Request $request)
    {
        $filtro = $request->input('filtro', '');

        if($request->input('chkProspect')){
            if($request->input('acao') == 'Ativar'){

                foreach($request->input('chkProspect') as $id){
                    $this->ativar($id);
                }
            }else if($request->input('acao') == 'Inativar'){

                foreach($request->input('chkProspect') as $id){
                    $this->inativar($id);
                }
            }else{

                foreach($request->input('chkProspect') as $id){
                    $this->remover($id);
                }
            }
        }

        $prospect = Prospect::select('status_prospect.*', 'prospect.*')
                    ->join('status_prospect', 'prospect.idstatus_prospect', 'status_prospect.id')
                    ->where(function ($query){
                        $query->where('prospect.ativo', '<>', 'E');
                        $query->where('status_prospect.ativo', '<>', 'E');
                    })
                    ->when($filtro, function($query) use ($filtro){
                        $query->where('prospect.nome', 'like', '%' . $filtro . '%');
                        $query->orWhere('prospect.telefone1', 'like', '%' . $filtro . '%');
                        $query->orWhere('prospect.telefone2', 'like', '%' . $filtro . '%');
                        $query->orWhere('prospect.ramal1', 'like', '%' . $filtro . '%');
                        $query->orWhere('prospect.ramal2', 'like', '%' . $filtro . '%');
                        $query->orWhere('prospect.ativo', '=', $filtro);
                        $query->orWhere('status_prospect.nome', 'like', '%' . $filtro . '%');
                    })
                    ->get();

        if (Auth::user()->hasAcesso("Prospect")) {

            return view('doctorservice.prospect.pesquisa', [
                'prospect' => $prospect,
                'filtro' => $filtro,
                'status' => StatusProspect::select('id', 'nome')->get()
            ]);
            
        } else {
            return redirect('/home')
                    ->with('error', 'Você não tem permissão para acessar a tela de Prospect!');
        }
    }

    public function buscarStatusAjax()
    {
        $status = StatusProspect::select('id', 'nome')->get();
        return response()->json($status);
    }


    public function cadastrar(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required',
            'apelido' => 'nullable|max:20',
            'email' => 'required|email',
            'descricao' => 'nullable|max:400',
            'telefone' => 'required',
            'ramal1' => 'nullable|numeric|min:4',
            'celular' => 'required',
            'ramal2' => 'nullable|numeric|min:4',
            'status' => 'required',
            'ativo' => 'required'
        ]);
        $prospect = new Prospect();
        $id = $prospect->insertGetId([
            'nome' => $request->nome,
            'apelido' => $request->apelido,
            'email' => $request->email,
            'telefone1' => preg_replace("/[^0-9]/", "", $request->telefone),
            'ramal1' => $request->ramal1,
            'telefone2' => preg_replace("/[^0-9]/", "", $request->celular),
            'ramal2' => $request->ramal2,
            'descricao' => $request->descricao,
            'idstatus_prospect' => $request->status,
            'ativo' => $request->ativo
        ]);
        return redirect()->route('prospect.cadastrado', ['id' => $id]);
        
    }

    public function cadastroCriado($id)
    {
        $usuarios = User::all();
        $prospectCadastrado = Prospect::latest('id')->first();
        $status = StatusProspect::select('id', 'nome')->get();
        $comunicacaoTipo = ComunicacaoTipo::select('id', 'nome')->get();
        $comunicacao = Comunicacao::latest('data')
                        ->where('idprospect', $id)
                        ->get();
                        
        return view('doctorservice.prospect.cadastroCriado')
            ->with('status', $status)
            ->with('prospectCadastrado', $prospectCadastrado)
            ->with('comunicacao', $comunicacao)
            ->with('comunicacaoTipo', $comunicacaoTipo)
            ->with('usuarios', $usuarios);
    }

    public function editar($id)
    {
        $usuarios = User::all();
        $statusProspect = StatusProspect::select('id', 'nome')->get();
        $prospect = Prospect::findOrFail($id);
        $comunicacaoTipo = ComunicacaoTipo::select('id', 'nome')->get();
        $comunicacao = Comunicacao::latest('data')
                        ->where('idprospect', $id)
                        ->get();
        return view('doctorservice.prospect.editar')
            ->with('prospect', $prospect)
            ->with('statusProspect', $statusProspect)
            ->with('comunicacao', $comunicacao)
            ->with('comunicacaoTipo', $comunicacaoTipo)
            ->with('usuarios', $usuarios);
    }

    public function atualizar(Request $request, $id)
    {
        $this->validate($request, [
            'nome' => 'required',
            'apelido' => 'nullable|max:20',
            'email' => 'required|email',
            'descricao' => 'nullable|max:400',
            'telefone' => 'required',
            'ramal1' => 'nullable|numeric|min:4',
            'celular' => 'required',
            'ramal2' => 'nullable|numeric|min:4',
            'status' => 'required',
            'ativo' => 'required'
        ]);
        if($request->input('remover')){
            $prospect = Prospect::findOrFail($id);
            if ($prospect->ativo != 'E'){
                $prospect->ativo = 'E';
                $prospect->save();
            }
            return redirect()->route('prospect');   
        }

        $prospect = Prospect::findOrFail($id);
        $prospect->nome = $request->nome;
        $prospect->apelido = $request->apelido;
        $prospect->email = $request->email;
        $prospect->telefone1 = preg_replace("/[^0-9]/", "", $request->telefone1);
        $prospect->ramal1 = $request->ramal1;
        $prospect->telefone2 = preg_replace("/[^0-9]/", "", $request->telefone2);
        $prospect->ramal2 = $request->ramal2;
        $prospect->descricao = $request->descricao;
        $prospect->idstatus_prospect = $request->status;
        $prospect->ativo = $request->ativo;
        $prospect->save();
        return redirect()->route('prospect');
    }

    private function remover($id)
    {
        $prospect = Prospect::find($id);
        $prospect->ativo = 'E';
        $prospect->save();
    }

    private function inativar($id)
    {
        $prospect = Prospect::find($id);
        $prospect->ativo = 'I';
        $prospect->save();
    }

    private function ativar($id)
    {
        $prospect = Prospect::find($id);
        $prospect->ativo = 'A';
        $prospect->save();
    }
}
