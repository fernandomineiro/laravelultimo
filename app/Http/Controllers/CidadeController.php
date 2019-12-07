<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cidade;
class CidadeController extends Controller
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
    public function buscar(Request $request)
    {

        $cidade = $request->input('cidade');

        $cidade = Cidade::where(['cidade' => $cidade])->first();

        if(is_null($cidade)){

            return redirect()->action("CidadeController@criar", ['request' => $request->all()]);
        }
        return response()->json([
            'cidade' => $cidade
        ]);
    }

    public function criar(Request $request)
    {

        $dados = $request->all();

        if(isset($dados['request'])){

            $estado = $dados['request']['uf'];
            $cidadeNome = $dados['request']['cidade'];
        }else{

            $estado = $dados['uf'];
            $cidadeNome = $dados['cidade'];
        }
        
        $cidade = new Cidade();
        $cidade->idestado = $estado;
        $cidade->cidade = $cidadeNome;
        $cidade->save();


        return response()->json([
            'cidade' => $cidade
        ]);
    }

    public function buscarPorEstado($id)
    {

        return response()->json([
            'cidades' => Cidade::where(['idestado' => $id])->orderBy('cidade')->get()
        ]);
    }
}
