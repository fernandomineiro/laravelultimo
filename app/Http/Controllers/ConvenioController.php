<?php

namespace App\Http\Controllers;

use App\Models\Convenio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConvenioController extends Controller
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
        return view('doctorservice.convenio.cadastro');
    }
    
    public function listar(Request $request)
    {
        $filtro = $request->input('filtro', '');

        if($request->input('chkConvenio')){
            if($request->input('acao') == 'Ativar'){

                foreach($request->input('chkConvenio') as $id){
                    $this->ativar($id);
                }
            }else if($request->input('acao') == 'Inativar'){

                foreach($request->input('chkConvenio') as $id){
                    $this->inativar($id);
                }
            }else{

                foreach($request->input('chkConvenio') as $id){
                    $this->remover($id);
                }
            }
        }

        $convenio = Convenio::select('id', 'nome', 'ativo')
                    ->where('ativo', '<>', 'E')
                    ->when($filtro, function($query) use ($filtro){
                        $query->where('convenio.nome', 'like', '%' . $filtro . '%');
                        $query->orWhere('convenio.ativo', '=', $filtro);
                    })
                    ->get();
                    
        if(Auth::user()->hasAcesso("Convênio")){

            return view('doctorservice.convenio.pesquisa', [
                'convenio' => $convenio,
                'filtro' => $filtro
            ]);

        } else {
            return redirect('/home')
                    ->with('error', 'Você não tem permissão para acessar a tela de Convênio!');
        }
    }

    public function cadastrar(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'status' => 'required'
        ]);
        $convenio = new Convenio();
        $convenio->nome = $request->nome;
        $convenio->descricao = $request->descricao;
        $convenio->ativo = $request->status;
        $convenio->save();
        return redirect()->route('convenio.listar');
    }
    
    public function editar($id)
    {  
        $convenio = Convenio::findOrFail($id);
        return view('doctorservice.convenio.editar')->with('convenio', $convenio);
    }

    public function atualizar(Request $request, $id)
    {
        $this->validate($request, [
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'status' => 'required'
        ]);
        if($request->input('remover')){
            $convenio = Convenio::findOrFail($id);
            if ($convenio->ativo != 'E'){
                $convenio->ativo = 'E';
                $convenio->save();
            }
            return redirect()->route('convenio.listar');   
        }
        $convenio = Convenio::findOrFail($id);
        $convenio->nome = $request->nome;
        $convenio->descricao = $request->descricao;
        $convenio->ativo = $request->status;
        $convenio->save();
        return redirect()->route('convenio.listar');
    }

    private function remover($id)
    {
        $convenio = Convenio::find($id);
        $convenio->ativo = 'E';
        $convenio->save();
    }

    private function inativar($id)
    {
        $convenio = Convenio::find($id);
        $convenio->ativo = 'I';
        $convenio->save();
    }

    private function ativar($id)
    {
        $convenio = Convenio::find($id);
        $convenio->ativo = 'A';
        $convenio->save();
    }

}
