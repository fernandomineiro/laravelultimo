<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parametro;
use App\Models\OperadoraUnidade;
use App\Models\Operadora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParametroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        


        $operadoraunidade = DB::table('operadora_unidade')->get();
        
        $parametro = Parametro::select('parametro.id as id',
            'operadora_unidade.nome as unidade',
            'parametro.confirmacao as confirmacao',
            'parametro.troca as troca',
            'parametro.cancelamento as cancelamento',
            'parametro.disputa as disputa',
            'parametro.checkpoint as checkpoint')
            ->join('operadora_unidade', 'operadora_unidade.idoperadora','parametro.idoperadora_unidade')
            ->get();

        



        return view('parametro.listar',[
            'operadoraunidade' => $operadoraunidade,
            'parametro' => $parametro
            
        ]);
    }
    public function mostrar()
    {
        
        


        $operadoraunidade = DB::table('operadora_unidade')->get();
        $operadora = DB::table('operadora')->get();
        

        return view('parametro.registro',[
            
            'operadora' => $operadora,
            'operadoraunidade' => $operadoraunidade
        ]);
    }

    public function salvar(Request $request)
    {
        $parametro = new Parametro();
        $parametro->idoperadora = $request->input('operadora');
        $parametro->idoperadora_unidade = $request->input('operadora_unidade');
        $parametro->confirmacao = $request->input('confirmacao');
        $parametro->troca = $request->input('troca');
        $parametro->cancelamento = $request->input('cancelamento');
        $parametro->disputa = $request->input('disputa');
        $parametro->checkpoint = $request->input('chekpoint');
        $parametro->ativo = 'A';
        $parametro->save();
        return redirect()->route('parametro');

       
        
    }

    public function listar(Request $request)
    {
        $filtro = $request->input('filtro', '');
       
        $operadoraunidade = DB::table('operadora_unidade')->get();
        $parametro = Parametro::select('parametro.id as id',
            'operadora_unidade.nome as unidade',
            'parametro.confirmacao as confirmacao',
            'parametro.troca as troca',
            'parametro.cancelamento as cancelamento',
            'parametro.disputa as disputa',
            'parametro.checkpoint as checkpoint')
            ->join('operadora_unidade', 'operadora_unidade.idoperadora','parametro.idoperadora_unidade')
            
            ->get();

            return view('parametro.listar',[
                'operadoraunidade' => $operadoraunidade,
                'parametro' => $parametro
                
            ]);
    }

   
    public function alterar($id)
    {
        $parametro = Parametro::where('id','=', $id)->get();

        $operadora = DB::table('operadora')->get();
        $operadoraunidade = DB::table('operadora_unidade')->get();
        

        return view('parametro.parametro',[
            'operadora' => $operadora,
            'operadoraunidade' => $operadoraunidade,
            'parametro' => $parametro
        ]);
    }

    public function storeAlterar($id, Request $request)
    {
        $idoperadora = $request->input('idoperadora');
        $idoperadora_unidade = $request->input('idoperadora_unidade');
        $confirmacao = $request->input('confirmacao');
        $troca = $request->input('troca');
        $cancelamento = $request->input('cancelamento');
        $disputa = $request->input('disputa');
        $checkpoint = $request->input('chekpoint');
        $ativo = $request->input('ativo');

        Parametro::where(['id' => $id])
        ->update([
            'idoperadora' => $idoperadora,
            'idoperadora_unidade' => $idoperadora_unidade,
            'confirmacao' => $confirmacao,
            'troca' => $troca,
            'cancelamento' => $cancelamento,
            'disputa' => $disputa,
            'checkpoint' => $checkpoint,
            'ativo' => $ativo
            ]);
            $operadoraunidade = DB::table('operadora_unidade')->get();
        $operadora = DB::table('operadora')->get();
        $parametro = DB::table('parametro')->get();

        return view('parametro.listar',[
            'parametro' => $parametro,
            'operadora' => $operadora,
            'operadoraunidade' => $operadoraunidade
        ]);
    }

    private function remover($id)
    {

        $parametro = Parametro::find($id);
        $parametro->ativo = 'E';
        $parametro->save();
    }

    private function inativar($id)
    {

        $parametro = Parametro::find($id);
        $parametro->ativo = 'I';
        $parametro->save();
    }

    private function ativar($id)
    {

        $parametro = Parametro::find($id);
        $parametro->ativo = 'A';
        $parametro->save();
    }

 
}
