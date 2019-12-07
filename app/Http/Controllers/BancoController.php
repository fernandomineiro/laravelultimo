<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BancoController extends Controller
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
        return view('doctorservice.banco.cadastro');
    }

    public function listar(Request $request)
    {
        $filtro = $request->input('filtro', '');

        if($request->input('chkBanco')){
            if($request->input('acao') == 'Ativar'){

                foreach($request->input('chkBanco') as $id){
                    $this->ativar($id);
                }
            }else if($request->input('acao') == 'Inativar'){

                foreach($request->input('chkBanco') as $id){
                    $this->inativar($id);
                }
            }else{

                foreach($request->input('chkBanco') as $id){

                    $this->remover($id);
                }
            }
        }

        $banco = Banco::select('id', 'numero', 'nome', 'ativo')
                    ->where('ativo', '<>', 'E')
                    ->when($filtro, function($query) use ($filtro){
                        $query->where('banco.numero', 'like', '%' . $filtro . '%');
                        $query->orWhere('banco.nome', 'like', '%' . $filtro . '%');
                        $query->orWhere('banco.ativo', '=', $filtro);
                    })
                    ->get();
                    
        if(Auth::user()->hasAcesso("Banco")){
            return view('doctorservice.banco.pesquisa', [
                'banco' => $banco,
                'filtro' => $filtro
            ]);
        } else {
            return redirect('/home')
                    ->with('error', 'Você não tem permissão para acessar a tela de Banco!');
        }
    }

    public function cadastrar(Request $request)
    {
        $this->validate($request, [
            'numero' => 'required|string|min:1|max:10',
            'nome' => 'required|string|max:255',
            'status' => 'required'
        ]);
        $banco = new Banco();
        $banco->numero = $request->numero;
        $banco->nome = $request->nome;
        $banco->ativo = $request->status;
        $banco->save();
        return redirect()->route('banco.listar');
    }

    public function editar($id)
    {
        $banco = Banco::findOrFail($id);
        return view('doctorservice.banco.editar')->with('banco', $banco);
    }

    public function atualizar(Request $request, $id)
    {
        $this->validate($request, [
            'numero' => 'required|string|min:1|max:10',
            'nome' => 'required|string|max:255',
            'status' => 'required'
        ]);
        if($request->input('remover')){
            $banco = Banco::findOrFail($id);
            if ($banco->ativo != 'E'){
                $banco->ativo = 'E';
                $banco->save();
            }
            return redirect()->route('banco.listar');   
        }
        $banco = Banco::findOrFail($id);
        $banco->numero = $request->numero;
        $banco->nome = $request->nome;
        $banco->ativo = $request->status;
        $banco->save();
        return redirect()->route('banco.listar');
    }

    private function remover($id)
    {
        $banco = Banco::find($id);
        $banco->ativo = 'E';
        $banco->save();
    }

    private function inativar($id)
    {
        $banco = Banco::find($id);
        $banco->ativo = 'I';
        $banco->save();
    }

    private function ativar($id)
    {
        $banco = Banco::find($id);
        $banco->ativo = 'A';
        $banco->save();
    }
    

}
