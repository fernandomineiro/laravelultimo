<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use App\Models\SalaEspecialidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EspecialidadeController extends Controller
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
        return view('doctorservice.especialidade.cadastro');
    }

    public function listar(Request $request)
    {
        $filtro = $request->input('filtro', '');

        if($request->input('chkEspecialidade')){
            if($request->input('acao') == 'Ativar'){

                foreach($request->input('chkEspecialidade') as $id){
                    $this->ativar($id);
                }
            }else if($request->input('acao') == 'Inativar'){

                foreach($request->input('chkEspecialidade') as $id){
                    $this->inativar($id);
                }
            }else{

                foreach($request->input('chkEspecialidade') as $id){
                    $this->remover($id);
                }
            }
        }

        $especialidade = Especialidade::select('id', 'nome', 'ativo')
                    ->where('ativo', '<>', 'E')
                    ->when($filtro, function($query) use ($filtro){
                        $query->where('especialidade.nome', 'like', '%' . $filtro . '%');
                        $query->orWhere('especialidade.ativo', '=', $filtro);
                    })
                    ->get();

        if (Auth::user()->hasAcesso("Especialidade")) {  

            return view('doctorservice.especialidade.pesquisa', [
                'especialidade' => $especialidade,
                'filtro' => $filtro
            ]);
            
        } else {
            return redirect('/home')
                    -> with('error', 'Você não tem permissão para acessar a tela de Especialidade!');
        }
    }

    public function cadastrar(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string|max:400',
            'status' => 'required'
        ]);
        $especialidade = new Especialidade();
        $especialidade->nome = $request->nome;
        $especialidade->descricao = $request->descricao;
        $especialidade->ativo = $request->status;
        $especialidade->save();
        return redirect()->route('especialidade.listar');
    }
    
    public function editar($id)
    {  
        $especialidade = Especialidade::findOrFail($id);
        return view('doctorservice.especialidade.editar')
            ->with('especialidade', $especialidade);
    }

    public function atualizar(Request $request, $id)
    {
        $this->validate($request, [
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string|max:400',
            'status' => 'required'
        ]);
        if($request->input('remover')){
            $especialidade = Especialidade::findOrFail($id);
            if ($especialidade->ativo != 'E'){
                $especialidade->ativo = 'E';
                $especialidade->save();
            }
            return redirect()->route('especialidade.listar');   
        }
        $especialidade = Especialidade::findOrFail($id);
        $especialidade->nome = $request->nome;
        $especialidade->descricao = $request->descricao;
        $especialidade->ativo = $request->status;
        $especialidade->save();
        return redirect()->route('especialidade.listar');
    }

    private function remover($id)
    {
        $especialidade = Especialidade::find($id);
        $especialidade->ativo = 'E';
        $especialidade->save();
    }

    private function inativar($id)
    {
        $especialidade = Especialidade::find($id);
        $especialidade->ativo = 'I';
        $especialidade->save();
    }

    private function ativar($id)
    {
        $especialidade = Especialidade::find($id);
        $especialidade->ativo = 'A';
        $especialidade->save();
    }

    public function buscarPorSala($id)
    {


        $especialidades = SalaEspecialidade::where(['idsala' => $id])->get();


        $especialidades->map(function ($especialidade){

            $especialidade->id = $especialidade->especialidade->id;
            $especialidade->nome = $especialidade->especialidade->nome;
        });


        return response()->json([
            'especialidades' => $especialidades
        ]);
    }

}
