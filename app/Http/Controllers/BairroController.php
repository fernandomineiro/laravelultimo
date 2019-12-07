<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bairro;

class BairroController extends Controller
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

        $bairro = $request->input('bairro');

        $bairroModel = Bairro::where(['bairro' => $bairro])->first();

        if(is_null($bairroModel)){

            return redirect()->action("BairroController@criar", ['request' => $request->all()]);
        }

        return response()->json([
            'bairro' => $bairro
        ]);
    }

    public function criar(Request $request)
    {

        $dados = $request->all();

        if(isset($dados['request'])){

            $bairroNome = $dados['request']['bairro'];
            $cidade = $dados['request']['cidade'];
        }else{

            $bairroNome = $dados['bairro'];
            $cidade = $dados['cidade'];
        }
        
        $bairro = new Bairro();
        $bairro->idcidade = $cidade;
        $bairro->bairro = $bairroNome;
        $bairro->save();


        return response()->json([
            'bairro' => $bairro
        ]);
    }



    public function buscarPorCidade($id)
    {

        return response()->json([
            'bairros' => Bairro::where(['idcidade' => $id])->orderBy('bairro')->get()
        ]);
    }
}
