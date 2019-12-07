<?php

namespace App\Http\Controllers;

use App\Models\Instituicao;
use App\Models\Pais;
use App\Models\Estado;
use App\Models\Cidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstituicaoController extends Controller
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
        return view('doctorservice.instituicao.cadastro')
            ->with('paises', Pais::all())
            ->with('estados', Estado::all())
            ->with('cidades', Cidade::all());
    }

    public function getCidade($id)
    {
        return response()->json(Cidade::where('idestado', $id)->get());
    }

    public function listar(Request $request)
    {
        $filtro = $request->input('filtro', '');

        if($request->input('chkInstituicao')){
            if($request->input('acao') == 'Ativar'){

                foreach($request->input('chkInstituicao') as $id){
                    $this->ativar($id);
                }
            }else if($request->input('acao') == 'Inativar'){

                foreach($request->input('chkInstituicao') as $id){
                    $this->inativar($id);
                }
            }else{

                foreach($request->input('chkInstituicao') as $id){
                    $this->remover($id);
                }
            }
        }

        $instituicao = Instituicao::select('id', 'nome', 'ativo')
                    ->where('ativo', '<>', 'E')
                    ->when($filtro, function($query) use ($filtro){
                        $query->where('instituicao.nome', 'like', '%' . $filtro . '%');
                        $query->orWhere('instituicao.ativo', '=', $filtro);
                    })
                    ->get();
                    
        if (Auth::user()->hasAcesso("Instituição")) {

            return view('doctorservice.instituicao.pesquisa', [
                'instituicao' => $instituicao,
                'filtro' => $filtro
            ]);

        } else {
            return redirect('/home')
                    ->with('error', 'Você não tem permissão para acessar a tela de Instituição!');
        }
    }

    public function cadastrar(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required|string|max:255',
            'pais' => 'required',
            'uf' => 'required',
            'cidade' => 'required',
            'status' => 'required'
        ]);
        $instituicao = new Instituicao();
        $instituicao->nome = $request->nome;
        $instituicao->descricao = $request->descricao;
        $instituicao->ativo = $request->status;
        $instituicao->cidade_idcidade = $request->cidade;
        $instituicao->save();
        return redirect()->route('instituicao.listar');
    }
    
    public function editar($id)
    {  
        $instituicao = Instituicao::findOrFail($id);
        return view('doctorservice.instituicao.editar')
            ->with('instituicao', $instituicao)
            ->with('paises', Pais::all())
            ->with('estados', Estado::all())
            ->with('cidades', Cidade::all());
    }

    public function atualizar(Request $request, $id)
    {
        $this->validate($request, [
            'nome' => 'required|string|max:255',
            'pais' => 'required',
            'uf' => 'required',
            'cidade' => 'required',
            'status' => 'required'
        ]);
        if($request->input('remover')){
            $instituicao = Instituicao::findOrFail($id);
            if ($instituicao->ativo != 'E'){
                $instituicao->ativo = 'E';
                $instituicao->save();
            }
            return redirect()->route('instituicao.listar');   
        }
        $instituicao = Instituicao::findOrFail($id);
        $instituicao->nome = $request->nome;
        $instituicao->descricao = $request->descricao;
        $instituicao->ativo = $request->status;
        $instituicao->cidade_idcidade = $request->cidade;
        $instituicao->save();
        return redirect()->route('instituicao.listar');
    }

    private function remover($id)
    {
        $instituicao = Instituicao::find($id);
        $instituicao->ativo = 'E';
        $instituicao->save();
    }

    private function inativar($id)
    {
        $instituicao = Instituicao::find($id);
        $instituicao->ativo = 'I';
        $instituicao->save();
    }

    private function ativar($id)
    {
        $instituicao = Instituicao::find($id);
        $instituicao->ativo = 'A';
        $instituicao->save();
    }

}
