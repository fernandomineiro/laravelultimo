<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use App\Models\Contato;
use App\Models\Endereco;
use App\Models\Operador;
use App\Models\Pessoa;
use App\Models\Operadora;
use App\Models\Perfil;
use App\Models\OperadoraUnidade;
use App\Models\PessoaFisica;
use App\Models\Users;
use App\Models\EstadoCivil;
use App\Models\Nacionalidade;
use App\Models\Pais;
use App\Models\Estado;
use App\Models\Cidade;
use App\Http\Requests\OperadorRequest;
use App\Repositories\ImageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class OperadorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        //dd(Auth::user());
        $logado = Users::join('perfil AS p','p.id','users.idperfil')->join('modulo AS m','m.id','p.idmodulo')->where(function($query){
            $query->where('m.id', '=', 2);
            $query->where('users.id', '=', Auth::user()->id);
        })->get(); 
        
        if(count($logado) != 0){
            $operadoras = Users::join('operador AS o', 'o.user_id', 'users.id')
                                ->join('operadora AS op','op.id','o.idoperadora')
                                ->join('pessoa AS p','p.id','op.idpessoa')
                                ->join('pessoa_juridica AS pj','pj.idpessoa','p.id')
                                ->where('users.id','=',Auth::user()->id)
                                ->select('op.id AS id','razao_social AS nome')
                                ->get();
             
        }else{
            $operadoras = Operadora::join('pessoa_juridica','pessoa_juridica.idpessoa','operadora.idpessoa')
                                    ->where('operadora.ativo', '=', 'A')
                                    ->select('operadora.id AS id','razao_social AS nome')
                                    ->get();
        }
        
        //modulo referente a operadora
        $perfis = Perfil::where('perfil.ativo', '<>', 'E')->where('perfil.idmodulo','=',2)->get();

        $pessoas = PessoaFisica::join('pessoa', 'pessoa.id', 'pessoa_fisica.idpessoa')->where(function($query){
            $query->where('pessoa_fisica.ativo', '=', 'A');
            $query->where('pessoa.tipo','=','PF');
        })->get();

        // dd($pessoas);
        $unidades = OperadoraUnidade::where(['ativo' => 'A'])->get();

        //verificar se usuário é de uma operadora então busca a operadora do usuário logado
        //if
        //else caso usuário logado não seja de uma operadora apresentar todas as operadoras
        
        $operador = new Operador();
        return view('operador.cadastro',[
            'operador' => $operador,
            'unidades' => $unidades,
            'perfis' => $perfis,
            'pessoas' => $pessoas,
            'operadoras' => $operadoras
        ]);
    }

    public function buscarUnidades($id)
    {
        return OperadoraUnidade::where(['ativo' => 'A', 'idoperadora' => $id])->get();
    }

    public function upload(Request $request, ImageRepository $image){
        $file = $request->file('foto');
        return $image->saveImage($file);
    }

    public function autocompleteOperadorPeloCPF(Request $request)
    {
        $dado = $request->query->get('term');
        $cpf = str_replace(array('-', '.'), '', $dado);
        $usuario = PessoaFisica::select('idpessoa', 'cpf', 'nome', 'sexo', 'data_nascimento')
            ->where('cpf', 'like', "%" . $cpf . "%")
            ->get();

        return response()->json($usuario);
    }

    public function store(OperadorRequest $request, ImageRepository $image)
    {
        $dadosPost = $request->post();

        $telefone = preg_replace("/[^0-9]/", "", $request->telefone);
        $telefone2 = preg_replace("/[^0-9]/", "", $request->telefone2);
        $celular = preg_replace("/[^0-9]/", "", $request->celular);
        $cpf = preg_replace("/[^0-9]/", "", $request->cpf);

        DB::beginTransaction();
        $operador = new Operador();
        $user = new Users;

        if($request->foto){
            $user->foto = $request->foto; 
        }
            
        $operador->telefone1 = $telefone;
        $operador->telefone2 = $telefone2;
        $operador->telefone3 = $celular;
        $operador->ramal1 = $dadosPost['ramal'];
        $operador->ramal2 = $dadosPost['ramal2'];
        $operador->ramal3 = $dadosPost['ramal3'];
        $operador->idoperadora = $dadosPost['operadora'];
        $operador->ativo = isset($dadosPost['status']) ? 'A' : 'I';
        $operador->idoperador_unidade = $dadosPost['unidade'];

        $user->idperfil = $dadosPost['perfil'];
        $user->name = $dadosPost['nome']; 
        $user->email = $dadosPost['email'];
        $user->password = Hash::make($dadosPost['senha']);
        $user->ativo = isset($dadosPost['status']) ? 'A' : 'I';
        $user->apelido = $dadosPost['apelido'];

        $pessoa = new Pessoa();
        $pessoa->ativo = isset($dadosPost['status']) ? 'A' : 'I';
        $pessoa->tipo = 'PF';

        if(!$pessoa->save()){
            DB::rollBack();
            return false;
        }
        $pessoaid = $pessoa->id;
        $operador->idpessoa = $pessoa->id;

        $estadoCivil = new EstadoCivil();
        $estadoCivil->ativo = 'A';
        $estadoCivil->estado = 'solteiro';
        
        if(!$estadoCivil->save()){
            DB::rollBack();
            return false;
        }

        $nacionalidade = new Nacionalidade();
        $nacionalidade->ativo = 'A';
        $nacionalidade->nacionalidade = 'brasil';
        
        if(!$nacionalidade->save()){
            DB::rollBack();
            return false;
        }
        $pessoa = PessoaFisica::where(['cpf' => $cpf])->first();
        // dd($pessoa);
        if($pessoa){
            PessoaFisica::where(['cpf' => $cpf])
                    ->update([
                        'nome' => $dadosPost['nome'],
                        'sexo' => $dadosPost['sexo'],
                        'data_nascimento' => $dadosPost['dataNascimento'],
                        'ativo' => isset($dadosPost['status']) ? 'A' : 'I'
                        ]);
            $operador->idpessoa = $pessoa->idpessoa;
        }else{
            $pessoaFisica = new PessoaFisica();
            $pessoaFisica->idpessoa = $pessoaid;
            $pessoaFisica->nome = $dadosPost['nome'];
            $pessoaFisica->ativo = isset($dadosPost['status']) ? 'A' : 'I';
            $pessoaFisica->cpf = $cpf;
            $pessoaFisica->sexo = $dadosPost['sexo'];
            $pessoaFisica->idestado_civil = $estadoCivil->id;
            $pessoaFisica->idnacionalidade = $nacionalidade->id;
            $pessoaFisica->data_nascimento = date('Y-m-d',strtotime($dadosPost['dataNascimento']));
            
            if(!$pessoaFisica->save()){
                DB::rollBack();
                return false;
            }
        }
        
        if(!$user->save()){
            DB::rollBack();
            return false;
        }
        $operador->user_id = $user->id;

        if(!$operador->save()){
            DB::rollBack();
            return false;
        }

        DB::commit();

        return redirect()->route('operador.listar');
    }

    public function listar(Request $request)
    {
        // dd($request->input());
        $filtro = $request->input('filtro','');
          
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

        $logado = Users::join('perfil AS p','p.id','users.idperfil')
            ->join('modulo AS m','m.id','p.idmodulo')
            ->where(function($query){
                $query->where('m.id', '=', 2);
                $query->where('users.id', '=', Auth::user()->id);
            })->get(); 

        if(count($logado) != 0){
            // retorna operadora do user logado
            $idoperadora = Users::join('operador AS o', 'o.user_id', 'users.id')
                            ->join('operadora AS op','op.id','o.idoperadora')
                            ->where('users.id','=',Auth::user()->id)
                            ->select('op.id AS id','o.idoperador_unidade AS unidade')
                            ->first();
            
            $unidades = OperadoraUnidade::where(function($query) use($idoperadora){
                $query->where('operadora_unidade.ativo', '=', 'A');
                $query->Where('operadora_unidade.id','=',$idoperadora->unidade);
            })->get();                            

            if($request->input('operadora') || $request->input('unidade') || $request->input('perfil') || $request->input('status')){

                $unidade = $request->input('unidade');
                $perfil = $request->input('perfil');
                $status = $request->input('status');
                
                $operadores = Operador::select('cpf','users.name AS nome','pessoa_juridica.razao_social AS operadora','operadora_unidade.nome AS unidade','perfil.nome AS perfil','operadora.id AS idoperadora','operador.id AS id','operador.ativo AS status')
                                        ->join('operadora', 'operador.idoperadora', 'operadora.id')
                                        //   ->join('pessoa', 'operador.idpessoa', 'pessoa.id')
                                        ->join('users', 'operador.user_id', 'users.id')
                                        ->join('perfil', 'users.idperfil', 'perfil.id')
                                        ->join('pessoa', 'pessoa.id', 'operador.idpessoa')
                                        ->join('pessoa_fisica','pessoa_fisica.idpessoa', 'pessoa.id')
                                        ->leftjoin('pessoa_juridica','pessoa_juridica.idpessoa','operadora.idpessoa')
                                        ->Join('operadora_unidade', 'operador.idoperador_unidade', 'operadora_unidade.id')
                                            ->when($unidade, function ($query) use ($unidade) {
                                                $query->where('operadora_unidade.id', $unidade);
                                                $query->where('operadora_unidade.ativo','<>', 'E');
                                                return;
                
                                            })->when($perfil, function ($query) use ($perfil) {
                                                return $query->where('perfil.id', $perfil);
                                            })
                                            ->when($status,function ($query) use ($status) {
                                                return $query->where('operador.ativo', $status);
                                            })
                                            ->where(function($query) use ($idoperadora){
                                        })->distinct()->get();
                // dd($operadores);
                
            }else{
                $operadores = Operador::select('cpf','users.name AS nome','pessoa_juridica.razao_social AS operadora','operadora_unidade.nome AS unidade','perfil.nome AS perfil','operadora.id AS idoperadora','operador.id AS id','operador.ativo AS status')
                                    ->join('operadora', 'operador.idoperadora', 'operadora.id')
                                    //   ->join('pessoa', 'operador.idpessoa', 'pessoa.id')
                                    ->join('users', 'operador.user_id', 'users.id')
                                    ->join('perfil', 'users.idperfil', 'perfil.id')
                                    ->join('pessoa', 'pessoa.id', 'operador.idpessoa')
                                    ->join('pessoa_fisica','pessoa_fisica.idpessoa', 'pessoa.id')
                                    ->leftjoin('pessoa_juridica','pessoa_juridica.idpessoa','operadora.idpessoa')
                                    ->Join('operadora_unidade', 'operador.idoperador_unidade', 'operadora_unidade.id')                                    //   ->join('operadora_grupo', 'operadora.id', 'operadora_grupo.idoperadora')
                                    // ->join('operadora', 'operador.idoperadora', 'operadora.id')
                                    // ->join('users', 'operador.user_id', 'users.id')
                                    // ->join('pessoa_fisica','operador.idpessoa', 'pessoa_fisica.idpessoa')
                                    // ->join('perfil', 'users.idperfil', 'perfil.id')
                                    // ->join('pessoa_juridica','operadora.idpessoa','pessoa_juridica.idpessoa')
                                    // ->join('operadora_unidade', 'operadora.id', 'operadora_unidade.idoperadora')
                                    ->where(function($query) use ($idoperadora){
                                        $query->where('operador.ativo', '<>', 'E');

                                    })
                                    ->when($filtro, function($query) use ($filtro) { 
    
                                        if($filtro == 'Ativo'){
                                            $filtro = 'A';
                                        }else if($filtro == 'Inativo'){
                                            $filtro = 'I';
                                        }
                                        $query->where(function($query) use ($filtro){
    
                                            $query->where('pessoa_fisica.cpf', 'like', '%' . $filtro . '%');
                                            $query->orWhere('users.name', 'like', '%' . $filtro . '%');
                                            $query->orWhere('razao_social', 'like', '%' . $filtro . '%');
                                            // $query->orWhere('operadora.nome', 'like', '%' . $filtro . '%');
                                            $query->orWhere('operadora_unidade.nome', 'like', '%' . $filtro . '%');
                                            $query->orWhere('perfil.nome', 'like', '%' . $filtro . '%');
                                            $query->orWhere('operador.ativo', '=', $filtro);
                                            $query->orWhere('cpf', '=', $filtro);
                                        });
                                    })->distinct()->get();
                //  dd($operadores);
            }
        }else{
            if($request->input('operadora') || $request->input('unidade') || $request->input('perfil') || $request->input('status')){

                $operadora = $request->input('operadora');
                $unidade = $request->input('unidade');
                $perfil = $request->input('perfil');
                $status = $request->input('status');
                
                $operadores = Operador::select('cpf','users.name AS nome','pessoa_juridica.razao_social AS operadora','operadora_unidade.nome AS unidade','perfil.nome AS perfil','operadora.id AS idoperadora','operador.id AS id','operador.ativo AS status')
                                        ->join('operadora', 'operador.idoperadora', 'operadora.id')
                                        //   ->join('pessoa', 'operador.idpessoa', 'pessoa.id')
                                        ->join('users', 'operador.user_id', 'users.id')
                                        ->join('perfil', 'users.idperfil', 'perfil.id')
                                        ->join('pessoa', 'pessoa.id', 'operador.idpessoa')
                                        ->join('pessoa_fisica','pessoa_fisica.idpessoa', 'pessoa.id')
                                        ->leftjoin('pessoa_juridica','pessoa_juridica.idpessoa','operadora.idpessoa')
                                        ->Join('operadora_unidade', 'operador.idoperador_unidade', 'operadora_unidade.id')
                                        ->where(function($query){
                                            $query->where('operador.ativo', '<>', 'E');
                                        })
                                        ->when($operadora, function ($query) use ($operadora) {
                                            return $query->where('operadora.id', $operadora);
            
                                        })->when($unidade, function ($query) use ($unidade) {
                                            return $query->where('operadora_unidade.id', $unidade);
            
                                        })->when($perfil, function ($query) use ($perfil) {
                                            return $query->where('perfil.id', $perfil);
                                        })
                                        ->when($status,function ($query) use ($status) {
                                            return $query->where('operador.ativo', $status);
                                        })->distinct()->get();
                                
    
            }else{
                $operadores = Operador::select('cpf','users.name AS nome','pessoa_juridica.razao_social AS operadora','operadora_unidade.nome AS unidade','perfil.nome AS perfil','operadora.id AS idoperadora','operador.id AS id','operador.ativo AS status')
                                        ->join('operadora', 'operador.idoperadora', 'operadora.id')
                                        //   ->join('pessoa', 'operador.idpessoa', 'pessoa.id')
                                        ->join('users', 'operador.user_id', 'users.id')
                                        ->join('perfil', 'users.idperfil', 'perfil.id')
                                        ->join('pessoa', 'pessoa.id', 'operador.idpessoa')
                                        ->join('pessoa_fisica','pessoa_fisica.idpessoa', 'pessoa.id')
                                        ->leftjoin('pessoa_juridica','pessoa_juridica.idpessoa','operadora.idpessoa')
                                        ->Join('operadora_unidade', 'operador.idoperadora', 'operadora_unidade.idoperadora')
                                    ->where(function($query){
                                        $query->where('operador.ativo', '<>', 'E');
                                    })
                                    ->when($filtro, function($query) use ($filtro) { 
    
                                        if($filtro == 'Ativo'){
                                            $filtro = 'A';
                                        }else if($filtro == 'Inativo'){
                                            $filtro = 'I';
                                        }
                                        $query->where(function($query) use ($filtro){
    
                                            $query->where('pessoa_fisica.cpf', 'like', '%' . $filtro . '%');
                                            $query->orWhere('users.name', 'like', '%' . $filtro . '%');
                                            $query->orWhere('razao_social', 'like', '%' . $filtro . '%');
                                            // $query->orWhere('operadora.nome', 'like', '%' . $filtro . '%');
                                            $query->orWhere('operadora_unidade.nome', 'like', '%' . $filtro . '%');
                                            $query->orWhere('perfil.nome', 'like', '%' . $filtro . '%');
                                            $query->orWhere('operador.ativo', '=', $filtro);
                                            $query->orWhere('cpf', '=', $filtro);
                                        });
                                    })->distinct()->get();
                // dd($operadores);
            }
        }

        $perfis = Perfil::where(function($query){
            $query->where('perfil.ativo', '=', 'A');
            $query->Where('perfil.idmodulo','=',2);
        })->get();

        if(count($logado) != 0){
            $unidades = OperadoraUnidade::where(function($query) use($idoperadora){
                $query->where('operadora_unidade.ativo', '=', 'A');
                $query->Where('operadora_unidade.idoperadora','=',$idoperadora->id);
            })->get();

            $operadoras = Operadora::join('pessoa_juridica as pj','operadora.idpessoa','pj.idpessoa')->where(function($query) use($idoperadora){
                $query->where('operadora.ativo', '=', 'A');
                $query->Where('operadora.id','=',$idoperadora->id);
            })->select('razao_social AS nome','pj.idpessoa as id')->get();
            // dd($operadoras);
        }else{
            $unidades = OperadoraUnidade::where(function($query){
                $query->where('operadora_unidade.ativo', '=', 'A');
            })->get();            

            $operadoras = Operadora::join('pessoa_juridica as pj','operadora.idpessoa','pj.idpessoa')->where(function($query){
                $query->where('operadora.ativo', '=', 'A');
            })->select('razao_social AS nome','pj.idpessoa as id')->get();
        }

        if (Auth::user()->hasAcesso("Operador")) {

            return view('operador.listar',[
                'operadores' => $operadores,
                'filtro' => $filtro,
                'perfis' => $perfis,
                'operadoras' => $operadoras,
                'unidades' => $unidades
            ]);

        } else {

            return redirect()->route('home')
                    ->with('error', 'Você não tem permissão para acessar a tela de Operador!');

        }
    }

    public function storeAlterar($id, OperadorRequest $request, ImageRepository $image )
    {
        $dadosPost = $request->post();

        $telefone = preg_replace("/[^0-9]/", "", $request->telefone);
        $telefone2 = preg_replace("/[^0-9]/", "", $request->telefone2);
        $celular = preg_replace("/[^0-9]/", "", $request->celular);
        $cpf = preg_replace("/[^0-9]/", "", $request->cpf);

        DB::beginTransaction();

        $operador = Operador::find($id);

        if($request->input('remover')){
            if ($operador->ativo != 'E'){
                $operador->ativo = 'E';
                $operador->save();
            }
            return redirect()->route('operador.listar');
        }

        Operador::where(['id' => $id])
                    ->update([                        
                        'telefone1' => $telefone,
                        'telefone2' => $telefone2,
                        'telefone3' => $celular,
                        'ramal1' => $dadosPost['ramal'],
                        'ramal2' => $dadosPost['ramal2'],
                        'ramal3' => $dadosPost['ramal3'],
                        'idoperadora' => $dadosPost['operadora'],
                        'idoperador_unidade' => $dadosPost['unidade'],
                        'ativo' => isset($dadosPost['status']) ? 'A' : 'I'
                    ]);

        $pessoaFisica = pessoaFisica::where(['idpessoa' => $operador->idpessoa])
                                        ->update([
                                            'cpf' => $cpf,
                                            'nome' => $dadosPost['nome'],
                                            'sexo' => $dadosPost['sexo'],
                                            'data_nascimento' => date("Y-m-d", strtotime($request->dataNascimento)),
                                            'ativo' => isset($dadosPost['status']) ? 'A' : 'I'
                                        ]);

        $user = Users::where(['id' => $operador->user_id ])
                            ->update([
                                'idperfil' => $dadosPost['perfil'],
                                'name' => $dadosPost['nome'],
                                'foto' => isset($request->foto) ? $request->foto : null,
                                'email' => $dadosPost['email'],
                                'password' => Hash::make($dadosPost['senha']),
                                'apelido' => $dadosPost['apelido'],
                                'ativo' => isset($dadosPost['status']) ? 'A' : 'I'
                            ]);

        DB::commit();

        return redirect()->route('operador.listar');
    }

    public function alterar($id)
    {

        $operadoras = Users::join('operador AS o', 'o.user_id', 'users.id')
                        ->join('operadora AS op','op.id','o.idoperadora')
                        ->join('pessoa AS p','p.id','op.idpessoa')
                        ->join('pessoa_juridica AS pj','pj.idpessoa','p.id')
                        ->where('users.id','=',Auth::user()->id)
                        ->select('op.id AS id','razao_social AS nome')
                        ->get();

        
            $operador = Operador::select('cpf',
                                    'users.name AS nome',
                                    'users.foto AS foto',
                                    'users.email AS email',
                                    'users.apelido AS apelido',
                                    'pessoa_juridica.razao_social AS operadora',
                                    'operadora.id AS idoperadora',
                                    'operador.idoperador_unidade AS idunidade',
                                    'perfil.nome AS perfil',
                                    'perfil.id AS idperfil',
                                    'operador.id AS id',
                                    'operador.ativo AS status',
                                    'pessoa_fisica.sexo AS sexo',
                                    'pessoa_fisica.data_nascimento AS dataNascimento',
                                    'operador.telefone1',
                                    'operador.ramal1',
                                    'operador.telefone2',
                                    'operador.ramal2',
                                    'operador.telefone3 AS celular',
                                    'operador.ramal3',
                                    )
                              ->join('operadora', 'operador.idoperadora', 'operadora.id')
                            //   ->join('pessoa', 'operador.idpessoa', 'pessoa.id')
                              ->join('users', 'operador.user_id', 'users.id')
                              ->join('pessoa_fisica','operador.idpessoa', 'pessoa_fisica.idpessoa')
                              ->join('perfil', 'users.idperfil', 'perfil.id')
                              ->join('pessoa_juridica','operadora.idpessoa','pessoa_juridica.idpessoa')
                              ->join('operadora_unidade', 'operadora.id', 'operadora_unidade.idoperadora')

                              ->where(['operador.id' => $id])->first();
        
        

        // $perfis = Perfil::where(['perfil.ativo' => 'A'])->get();
        $perfis = Perfil::where('perfil.ativo', '<>', 'E')->where('perfil.idmodulo','=',2)->get();
        // $pessoas = Pessoa::join('pessoa_fisica', 'pessoa_fisica.idpessoa', 'pessoa.id')->where(['pessoa.tipo' => 'PF'])->get();
        // $unidades = OperadoraUnidade::where(['ativo' => 'A'])->get();
        // $operadoras = Operadora::join('operadora_grupo', 'operadora_grupo.idoperadora', 'operadora.id')->where(['operadora_grupo.ativo' => 'A'])->get();
        
        // dd($operador);

        return view('operador.cadastro',[
            'operador' => $operador,
            'operadoras' => $operadoras,
            'perfis' => $perfis
        ]);
    }

    private function remover($id)
    {

        $operador = Operador::find($id);
        $operador->ativo = 'E';
        $operador->save();
    }

    private function inativar($id)
    {
        // dd($id);
        $operador = Operador::find($id);
        $operador->ativo = 'I';
        $operador->save();
    }

    private function ativar($id)
    {

        $operador = Operador::find($id);
        $operador->ativo = 'A';
        $operador->save();
    }
}
