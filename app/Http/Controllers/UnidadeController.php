<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Bairro;
use App\Models\Contato;
use App\Models\Endereco;
use App\Models\Pessoa;
use App\Models\Operadora;
use App\Models\Perfil;
use App\Models\OperadoraUnidade;
use App\Models\OperadoraUnidadeFoto;
use App\Models\TipoContato;
use App\Models\TipoEndereco;
use App\Repositories\ImageRepository;
use Illuminate\Http\Request;
use App\Models\Pais;
use App\Models\Estado;
use App\Models\Cidade;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UnidadeRequest;
use Illuminate\Support\Facades\Auth;



class UnidadeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $paises = Pais::get();
        $unidade = new OperadoraUnidade();

        return view('unidade.cadastro',[
            'paises' => $paises,
            'unidade' => $unidade
        ]);
    }
    
    public function upload(Request $request, ImageRepository $image){
        $file = $request->file('foto');
        
        foreach($file as $imagem){
            return $image->saveImage($imagem); 
        }
    }

    public function store(UnidadeRequest $request, ImageRepository $image)
    {
        $telefone = preg_replace("/[^0-9]/", "", $request->telefone);
        
        $operadora = User::join('operador', 'operador.user_id', 'users.id')
            ->where('users.id', Auth::user()->id)
            ->select('operador.idoperadora')
            ->first();
        // $operadora = Users::join('operador', 'operador.user_id', 'users.id')
        //         ->join('operadora','operadora.id','operador.idoperadora')
        //         ->where('users.id','=',Auth::user()->id)
        //         ->select('operadora.id')
        //         ->first();

        $idUnidade = OperadoraUnidade::insertGetId([
            'nome' => $request->nome,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'idoperadora' => $operadora->id,
            'telefone' => $telefone,
            'Endereco' => $request->endereco,
            'bairro' => $request->bairro,
            'cep' => $request->cep,
            'cidade' => $request->cidade,
            'UF' => $request->uf,
            'pais' => $request->pais,
            'ativo' => $request->status,
        ]);
        
        return redirect()->route('unidade.alterar', $idUnidade);
    }

    public function storeImage(Request $request)
    {
        if($request->foto){
            
            $i = 0;
            $contadorImagens = $request->foto;

            foreach($contadorImagens as $imagem){
                $unidadeFoto = new OperadoraUnidadeFoto();
                $unidadeFoto->arquivo = $imagem;
                $unidadeFoto->legenda = $request->legenda[$i];
                $unidadeFoto->ativo = $request->ativo_foto;
                $unidadeFoto->ordem = ++$i;
                $unidadeFoto->idoperadora_unidade = $request->idoperadora_unidade;
                $unidadeFoto->save();
            }
        }

        return redirect()->route('unidade.alterar', $request->idoperadora_unidade);
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

        $logado = User::join('perfil AS p','p.id','users.idperfil')
                    ->join('modulo AS m','m.id','p.idmodulo')
                    ->where(function($query){
                        $query->where('m.id', '=', 2);
                        $query->where('users.id', '=', Auth::user()->id);
                    })->get();
        
        if(count($logado) != 0){
            // retorna operadora do user logado
            $operadora = User::join('operador AS o', 'o.user_id', 'users.id')
                                    ->join('operadora AS op','op.id','o.idoperadora')
                                    ->where('users.id','=',Auth::user()->id)
                                    ->select('op.id AS id')
                                    ->first();

            $unidades = OperadoraUnidade::select(
                                DB::raw('count(ordem) as fotos'), 
                                'nome', 
                                'telefone', 
                                'cidade.cidade', 
                                'estado.estado', 
                                'operadora_unidade.ativo', 
                                'operadora_unidade.id'
                            )
                            ->join('cidade','cidade.id','operadora_unidade.cidade')
                            ->join('estado','estado.id','operadora_unidade.UF')
                            ->join('operadora_unidade_foto','idoperadora_unidade','operadora_unidade.id')
                            ->where(function($query) use ($operadora){
                                $query->where('operadora_unidade.ativo', '<>', 'E');
                                $query->where('idoperadora', $operadora->id);
                            })
                            ->groupBy('operadora_unidade.ativo', 'nome', 'telefone', 'cidade.cidade', 'estado.estado', 'operadora_unidade.id')
                            ->when($filtro, function($query) use ($filtro) { 
                                if($filtro == 'Ativo'){
                                    $filtro = 'A';
                                }else if($filtro == 'Inativo'){
                                    $filtro = 'I';
                                }
                                $query->where(function($query) use ($filtro){
                                    $query->where('operadora_unidade.nome', 'like', '%' . $filtro . '%');
                                    $query->orWhere('telefone', 'like', '%' . $filtro . '%');
                                    $query->orWhere('cidade.cidade', 'like', '%' . $filtro . '%');
                                    $query->orWhere('estado.estado', 'like', '%' . $filtro . '%');
                                    $query->orWhere('operadora_unidade.ativo', '=', $filtro);
                                });  
                            })
                            ->orderBy('operadora_unidade.id', 'asc')
                            ->get();

        }else{
            $unidades = OperadoraUnidade::select(DB::raw('count(ordem) as fotos'),'nome','telefone','cidade.cidade as cidade','estado.estado as estado', 'operadora_unidade.ativo as status','operadora_unidade.id AS id')
                        ->join('cidade','cidade.id','operadora_unidade.cidade')
                        ->join('estado','estado.id','operadora_unidade.UF')
                        ->join('operadora_unidade_foto','idoperadora_unidade','operadora_unidade.id')
                        ->where(function($query){
                            $query->where('operadora_unidade.ativo', '<>', 'E');
                        })
                        ->when($filtro, function($query) use ($filtro) { 
                            if($filtro == 'Ativo'){
                                $filtro = 'A';
                            }else if($filtro == 'Inativo'){
                                $filtro = 'I';
                            }
                        $query->where(function($query) use ($filtro){

                            $query->where('operadora_unidade.nome', 'like', '%' . $filtro . '%');
                            $query->orWhere('telefone', 'like', '%' . $filtro . '%');
                            $query->orWhere('cidade.cidade', 'like', '%' . $filtro . '%');
                            $query->orWhere('estado.estado', 'like', '%' . $filtro . '%');
                            $query->orWhere('operadora_unidade.ativo', '=', $filtro);
                        });  
                       })->orderBy('ordem')
                       ->get();
        }

        if (Auth::user()->hasAcesso("Unidade")) {

            return view('unidade.listar',[
                'unidades' => $unidades,
                'filtro' => $filtro
            ]);

        } else {

            return redirect()->route('home')
                ->with('error', 'Você não tem permissão para acessar a tela de Unidade!');

        }
    }

    public function storeAlterar($id, UnidadeRequest $request)
    {
        // dd($request->all());
        $dadosPost = $request->post();
        $telefone = preg_replace("/[^0-9]/", "", $request->telefone);
        
        DB::beginTransaction();

        $unidade = OperadoraUnidade::find($id);

        OperadoraUnidade::where(['id' => $id])
                ->update([
                    'nome' => $dadosPost['nome'],
                    'telefone' => $telefone,
                    'ativo' => isset($dadosPost['status']) ? 'A' : 'I',
                    'latitude' => $dadosPost['latitude'],
                    'longitude' => $dadosPost['longitude'],
                    'endereco' => $dadosPost['endereco'],
                    'cep' => $dadosPost['cep'],
                    'bairro' => $dadosPost['bairro'],
                    'cidade' => $dadosPost['cidade'],
                    'uf' => $dadosPost['uf'],
                    // 'logotipo' => $request->hasFile('logotipo') ? $image->saveImage($request->logotipo) : $operador->logotipo,
                    'pais' => $dadosPost['pais']  
                    ]);

        DB::commit();

        return redirect()->route('unidade.listar');
    }

    public function alterar($id)
    {
        $paises = Pais::get();

        $unidade = OperadoraUnidade::where(['id' => $id])->first();
        $imagens = OperadoraUnidadeFoto::where(['idoperadora_unidade' => $id])->get();

        return view('unidade.cadastro',[
            'unidade' => $unidade,
            'imagens' => $imagens,
            'paises' => $paises,
            'cidades' => json_encode(Cidade::where(['idestado' => $unidade->UF])->get()),
            'estados' => json_encode(Estado::where(['idpais' => $unidade->pais])->get()),
            'bairros' => json_encode(Bairro::where(['idcidade' => $unidade->cidade])->get()),
        ]);
    }

    private function remover($id)
    {

        $unidade = OperadoraUnidade::find($id);
        $unidade->ativo = 'E';
        $unidade->save();
    }

    private function inativar($id)
    {

        $unidade = OperadoraUnidade::find($id);
        $unidade->ativo = 'I';
        $unidade->save();
    }

    private function ativar($id)
    {

        $unidade = OperadoraUnidade::find($id);
        $unidade->ativo = 'A';
        $unidade->save();
    }
}
