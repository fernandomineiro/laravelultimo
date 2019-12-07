<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Modulo;
use App\Models\Perfil;
use App\Models\Pessoa;
use App\Models\PessoaFisica;
use App\Models\DoctorService;
use App\Models\Operador;
use App\Models\Operadora;
use App\Models\OperadoraUnidade;
use App\Models\Medico;
use App\Models\EstadoCivil;
use App\Models\Nacionalidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Repositories\ImageRepository;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $modulos = Modulo::where('ativo', '=', 'A')
                    ->where('nome', '!=', 'Geral')
                    ->get();

        $usuarios = PessoaFisica::select('pessoa.id', 'nome', 'cpf', 'data_nascimento', 'sexo')
                        ->join('pessoa', 'pessoa_fisica.idpessoa', '=', 'pessoa.id')
                        ->get();

        $estadoCivil = EstadoCivil::where('ativo', '=', 'A')->get();

        $operadoras = Operadora::select('operadora.id', 'pessoa_juridica.nome_fantasia as nome_operadora')
                            ->join('pessoa_juridica', 'operadora.idpessoa', 'pessoa_juridica.idpessoa')
                            ->get();

        return view('doctorservice.usuario.cadastro')
            ->with('modulos', $modulos)
            ->with('usuarios', $usuarios)
            ->with('estadoCivil', $estadoCivil)
            ->with('operadoras', $operadoras);
    }

    public function listar(Request $request)
    {
        $filtro = $request->input('filtro', '');
        

        if($request->input('chkUsuario')){
            if($request->input('acao') == 'Ativar'){

                foreach($request->input('chkUsuario') as $id){
                    $this->ativar($id);
                }
            }else if($request->input('acao') == 'Inativar'){

                foreach($request->input('chkUsuario') as $id){
                    $this->inativar($id);
                }
            }else{

                foreach($request->input('chkUsuario') as $id){
                    $this->remover($id);
                }
            }
        }

        $doctorService = DoctorService::select('users.id',
                                               'users.name as nome',
                                               'pessoa_fisica.cpf', 
                                               'perfil.nome as perfil', 
                                               'modulo.nome as modulo',
                                               'users.ativo')
                                            ->join('users', 'doctor_service.iduser', 'users.id')
                                            ->join('pessoa', 'doctor_service.idpessoa', 'pessoa.id')
                                            ->join('pessoa_fisica', 'pessoa.id', 'pessoa_fisica.idpessoa')
                                            ->join('perfil', 'users.idperfil', 'perfil.id')
                                            ->join('modulo', 'perfil.idmodulo', 'modulo.id')
                                            ->when($filtro, function ($query) use ($filtro) {
                                                if ($filtro == 'Ativo') {
                                                    $filtro = 'A';
                                                } else if ($filtro == 'Inativo') {
                                                    $filtro = 'I';
                                                }
                                                $query->where(function ($query) use ($filtro) {
                                                    $query->orwhere('cpf', 'like', '%' . $filtro . '%');
                                                    $query->orWhere('name', 'like', '%' . $filtro . '%');
                                                    $query->orWhere('perfil.nome', 'like', '%' . $filtro . '%');
                                                    $query->orWhere('modulo.nome', 'like', '%' . $filtro . '%');
                                                    $query->orWhere('users.ativo', '=', $filtro);
                                                });
                                            });
                                            
        $operador = Operador::select('users.id',
                                     'users.name as nome', 
                                     'pessoa_fisica.cpf', 
                                     'perfil.nome as perfil', 
                                     'modulo.nome as modulo',
                                     'users.ativo')
                                    ->join('users', 'operador.user_id', 'users.id')
                                    ->join('pessoa', 'operador.idpessoa', 'pessoa.id')
                                    ->join('pessoa_fisica', 'pessoa.id', 'pessoa_fisica.idpessoa')
                                    ->join('perfil', 'users.idperfil', 'perfil.id')
                                    ->join('modulo', 'perfil.idmodulo', 'modulo.id')
                                    ->when($filtro, function ($query) use ($filtro) {
                                        if ($filtro == 'Ativo') {
                                            $filtro = 'A';
                                        } else if ($filtro == 'Inativo') {
                                            $filtro = 'I';
                                        }
                                        $query->where(function ($query) use ($filtro) {
                                            $query->orwhere('cpf', 'like', '%' . $filtro . '%');
                                            $query->orWhere('name', 'like', '%' . $filtro . '%');
                                            $query->orWhere('perfil.nome', 'like', '%' . $filtro . '%');
                                            $query->orWhere('modulo.nome', 'like', '%' . $filtro . '%');
                                            $query->orWhere('users.ativo', '=', $filtro);
                                        });
                                    });

        $medicos = Medico::select('users.id',
                                  'users.name as nome',
                                  'pessoa_fisica.cpf', 
                                  'perfil.nome as perfil', 
                                  'modulo.nome as modulo',
                                  'users.ativo')
                                ->join('users', 'medico.user_id', 'users.id')
                                ->join('pessoa', 'medico.idpessoa', 'pessoa.id')
                                ->join('pessoa_fisica', 'pessoa.id', 'pessoa_fisica.idpessoa')
                                ->join('perfil', 'users.idperfil', 'perfil.id')
                                ->join('modulo', 'perfil.idmodulo', 'modulo.id')
                                ->when($filtro, function ($query) use ($filtro) {
                                    if ($filtro == 'Ativo') {
                                        $filtro = 'A';
                                    } else if ($filtro == 'Inativo') {
                                        $filtro = 'I';
                                    }
                                    $query->where(function ($query) use ($filtro) {
                                        $query->orwhere('cpf', 'like', '%' . $filtro . '%');
                                        $query->orWhere('name', 'like', '%' . $filtro . '%');
                                        $query->orWhere('perfil.nome', 'like', '%' . $filtro . '%');
                                        $query->orWhere('modulo.nome', 'like', '%' . $filtro . '%');
                                        $query->orWhere('users.ativo', 'like', '%' . $filtro . '%');
                                    });
                                });

        $usuarios = $doctorService->union($operador)->union($medicos)
                    ->orderBy('id', 'asc')
                    ->where('users.ativo', '<>', 'E')
                    ->get();
        
        $modulos = Modulo::where('nome', '!=', 'Geral');
        $perfil = Perfil::all();

        if (Auth::user()->hasAcesso("Usuário")) {

            return view('doctorservice.usuario.pesquisa')
                ->with('filtro', $filtro)
                ->with('modulos', $modulos)
                ->with('perfil', $perfil)
                ->with('usuarios', $usuarios);
            
        } else {
            return redirect('/home')
                    ->with('error', 'Você não tem permissão para acessar a tela de Usuário!');
        }        
    }

    public function getPerfis()
    {
        return response()->json(Perfil::all());
    }

    public function buscarPerfis($id)
    {
        $perfil = Perfil::where('idmodulo', '=', $id)
                    ->where('ativo', '=', 'A')
                    ->get();
        return response()->json($perfil);
    }

    public function autocompleteUsuarioPeloCPF(Request $request)
    {
        $dado = $request->query->get('term');
        $cpf = str_replace(array('-', '.'), '', $dado);
        $usuario = PessoaFisica::select('idpessoa', 'cpf', 'nome', 'sexo', 'data_nascimento', 'idestado_civil')
            ->where('cpf', 'like', "%" . $cpf . "%")
            ->get();

        return response()->json($usuario);
    }

    public function upload(Request $request, ImageRepository $image)
    {
        $file = $request->file('foto');
        
        if ($request->hasFile('foto')) {
            return $image->saveImage($request->foto);
        }
    }

    public function getUnidades($idOperadora)
    {
        return response()->json(OperadoraUnidade::where(['idoperadora' => $idOperadora])->where(['ativo' => 'A'])->get());
    }

    public function cadastrar(Request $request, ImageRepository $image)
    {
        // dd($request->all());
        $this->validate($request, [
            'modulo' => 'required',
            'perfil' => 'required',
            'cpf' => 'required|cpf',
            'nome' => 'required|string|max:255',
            'sexo' => 'required|in:M,F',
            'estado_civil' => 'required',
            'data_nascimento' => 'required|date_format:d/m/Y',
            'apelido' => 'max:45',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[@!$#%]).*$/',
            'password_confirmation' => 'same:password|min:8'
        ]);

        
        $dados = $request->all();
        
        DB::beginTransaction();
                
        if(empty($dados['idpessoa'])){
            $pessoa = new Pessoa();
            $pessoa->ativo = 'A';
            $pessoa->tipo = 'PF';

            if(!$pessoa->save()){
                DB::rollBack();
                return false;
            }

            $nacionalidade = new Nacionalidade();
            $nacionalidade->ativo = 'A';
            $nacionalidade->nacionalidade = 'brasileiro(a)';
            
            if(!$nacionalidade->save()){
                DB::rollBack();
                return false;
            }

            $pessoaFisica = new PessoaFisica();
            $pessoaFisica->nome = $dados['nome'];
            $pessoaFisica->cpf = str_replace(array('.', '-'), '', $dados['cpf']);
            $pessoaFisica->idestado_civil = $dados['estado_civil'];
            $pessoaFisica->sexo = $dados['sexo'];
            $pessoaFisica->data_nascimento = date('Y-m-d', strtotime(str_replace('/', '-', $dados['data_nascimento'])));
            $pessoaFisica->idpessoa = $pessoa->id;
            $pessoaFisica->ativo = isset($dados['status']) ? 'A' : 'I';
            $pessoaFisica->idnacionalidade = $nacionalidade->id;

            if(!$pessoaFisica->save()){
                DB::rollBack();
                return false;
            }
        }
        
        $user = new Users();
        $user->name = $dados['nome'];
        $user->email = $dados['email'];
        
        if($dados['password'] === $dados['password_confirmation']){
            $user->password = Hash::make($dados['password']);
        }
        $user->apelido = $dados['apelido'];
        $user->ativo = $dados['status'];
        $user->idperfil = $dados['perfil'];

        if($request->hasFile('foto')) {
            $user->foto = $request->foto;
        }

        // if ($request->hasFile('foto') && $request->foto->isValid()) {
        //     $extension = $request->foto->extension();
        //     $name = time() . random_int(100, 999);
        //     $fileName = "{$name}.{$extension}";
        //     $path = $request->foto->store('usuarios');
        //     $user->foto = $path;
        // }

        if(!$user->save()){
            DB::rollBack();
            return false;
        }

        if($dados['modulo'] == 1){
            $doctorService = new DoctorService();
            $doctorService->data_admissao = date('Y-m-d');
            $doctorService->ativo = 'A';
            $doctorService->idpessoa = empty($dados['idpessoa']) ? $pessoa->id : $dados['idpessoa'];
            $doctorService->iduser = $user->id;
            if(!$doctorService->save()){
                DB::rollBack();
                return false;
            }
        }

        if($dados['modulo'] == 2){
            $operador = new Operador();
            $operador->idoperadora = $dados['operadora'];
            $operador->idoperador_unidade = $dados['unidade'];
            $operador->ativo = 'A';
            $operador->idpessoa = empty($dados['idpessoa']) ? $pessoa->id : $dados['idpessoa'];
            $operador->user_id = $user->id;
            if(!$operador->save()){
                DB::rollBack();
                return false;
            }
        }

        if($dados['modulo'] == 3){
            $medico = new Medico();
            $medico->idpessoa = empty($dados['idpessoa']) ? $pessoa->id : $dados['idpessoa'];
            $medico->user_id = $user->id;
            $medico->ativo = 'A';
            if($request->hasFile('foto')) {
                $medico->foto = $request->foto;
            }
            if(!$medico->save()){
                DB::rollBack();
                return false;
            }
        }

        DB::commit();

        return redirect()->route('usuario.listar');
    }

    public function editar($id)
    {
        $user = Users::select('users.*','idmodulo')
                ->join('perfil', 'users.idperfil', 'perfil.id')
                ->where(['users.id' => $id])->get();
                
        if($user[0]->idmodulo == 1){

            $usuario = $user[0]->select('users.*',
                                        'idmodulo', 
                                        'doctor_service.idpessoa', 
                                        'pessoa_fisica.cpf', 
                                        'pessoa_fisica.idestado_civil', 
                                        'pessoa_fisica.sexo', 
                                        'pessoa_fisica.data_nascimento')
                            ->join('doctor_service', 'users.id', 'doctor_service.iduser')
                            ->join('perfil', 'users.idperfil', 'perfil.id')
                            ->join('pessoa_fisica', 'doctor_service.idpessoa', 'pessoa_fisica.idpessoa')
                            ->where(['users.id' => $id])
                            ->get();
                            
        } else if ($user[0]->idmodulo == 2) {

            $usuario = $user[0]->select('users.*', 
                                        'operador.idpessoa',
                                        'operador.idoperadora',
                                        'operador.idoperador_unidade',
                                        'idmodulo', 
                                        'pessoa_fisica.cpf', 
                                        'pessoa_fisica.idestado_civil', 
                                        'pessoa_fisica.sexo', 
                                        'pessoa_fisica.data_nascimento')
                            ->join('operador', 'users.id', 'operador.user_id')
                            ->join('perfil', 'users.idperfil', 'perfil.id')
                            ->join('pessoa_fisica', 'operador.idpessoa', 'pessoa_fisica.idpessoa')
                            ->where(['users.id' => $id])
                            ->get();

        } else if ($user[0]->idmodulo == 3) {
            
            $usuario = $user[0]->select('users.*', 
                                        'medico.idpessoa', 
                                        'idmodulo', 
                                        'pessoa_fisica.cpf', 
                                        'pessoa_fisica.idestado_civil', 
                                        'pessoa_fisica.sexo', 
                                        'pessoa_fisica.data_nascimento')
                            ->join('medico', 'users.id', 'medico.user_id')
                            ->join('perfil', 'users.idperfil', 'perfil.id')
                            ->join('pessoa_fisica', 'medico.idpessoa', 'pessoa_fisica.idpessoa')
                            ->where(['users.id' => $id])
                            ->get();
                            
        }
        $usuario = $usuario[0];
        
        $modulos = Modulo::where('nome', '!=', 'Geral')->get();

        $pessoas = Pessoa::select('pessoa.id', 'nome', 'cpf', 'data_nascimento', 'sexo')
                        ->join('pessoa_fisica',  'pessoa.id', '=', 'pessoa_fisica.idpessoa')
                        ->get();

        $estadoCivil = EstadoCivil::all();

        $operadoras = Operadora::select('operadora.id', 'pessoa_juridica.nome_fantasia as nome_operadora')
                            ->join('pessoa_juridica', 'operadora.idpessoa', 'pessoa_juridica.idpessoa')
                            ->get();

        return view('doctorservice.usuario.editar')
            ->with('usuario', $usuario)
            ->with('modulos', $modulos)
            ->with('pessoas', $pessoas)
            ->with('estadoCivil', $estadoCivil)
            ->with('operadoras', $operadoras);

    }

    private function updatePessoaFisicaDoctorService($arrayDados, $id)
    {
        $idPessoaDoctorService = DoctorService::select('idpessoa')
            ->where(['iduser' => $id])->get();
        
        PessoaFisica::where(['idpessoa' => $idPessoaDoctorService])
            ->update([
                'nome' => $arrayDados['nome'],
                'cpf' =>  str_replace(array('.', '-'), '', $arrayDados['cpf']),
                'idestado_civil' => $arrayDados['estado_civil'],
                'sexo' => $arrayDados['sexo'],
                'data_nascimento' => date('Y-m-d', strtotime(str_replace('/', '-', $arrayDados['data_nascimento']))),
                'idpessoa' => $idPessoaDoctorService,
                'ativo' => $arrayDados['status']
            ]);
    }

    private function updatePessoaFisicaOperador($arrayDados, $id)
    {
        $idPessoaOperador = Operador::select('idpessoa')
            ->where(['user_id' => $id])->get();

        Operador::where(['user_id' => $id])
            ->update(['idoperador_unidade' => $arrayDados['unidade']]);
        
        PessoaFisica::where(['idpessoa' => $idPessoaOperador])
            ->update([
                'nome' => $arrayDados['nome'],
                'cpf' => str_replace(array('.', '-'), '', $arrayDados['cpf']),
                'idestado_civil' => $arrayDados['estado_civil'],
                'sexo' => $arrayDados['sexo'],
                'data_nascimento' => date('Y-m-d', strtotime(str_replace('/', '-', $arrayDados['data_nascimento']))),
                'idpessoa' => $idPessoaOperador,
                'ativo' => $arrayDados['status']
            ]);
    }

    private function updatePessoaFisicaMedico($arrayDados, $id)
    {
        $idPessoaMedico = Medico::select('idpessoa')
            ->where(['user_id' => $id])->get();
        
        PessoaFisica::where(['idpessoa' => $idPessoaMedico])
            ->update([
                'nome' => $arrayDados['nome'],
                'cpf' => str_replace(array('.', '-'), '', $arrayDados['cpf']),
                'idestado_civil' => $arrayDados['estado_civil'],
                'sexo' => $arrayDados['sexo'],
                'data_nascimento' => date('Y-m-d', strtotime(str_replace('/', '-', $arrayDados['data_nascimento']))),
                'idpessoa' => $idPessoaMedico,
                'ativo' => $arrayDados['status']
            ]);

        $medico = Medico::where(['user_id' => $id])->first();
        
        if ($arrayDados['foto'] != "") {
            $medico->foto = $arrayDados['foto'];
            $medico->save();
        }
    }

    public function atualizar(Request $request, ImageRepository $image, $id)
    {
        // dd($request->all());
        $this->validate($request, [
            'modulo' => 'required',
            'perfil' => 'required',
            'cpf' => 'required|cpf',
            'nome' => 'required|string|max:255',
            'sexo' => 'required|in:M,F',
            'estado_civil' => 'required',
            'data_nascimento' => 'required|date_format:d/m/Y',
            'apelido' => 'max:45',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[@!$#%]).*$/',
            'password_confirmation' => 'same:password|min:8'
        ]);

        if($request->input('remover')){
            $usuario = Users::findOrFail($id);
            if ($usuario->ativo != 'E'){
                $usuario->ativo = 'E';
                $usuario->save();
            }
            return redirect()->route('usuario.listar');   
        }

        $dadosUpdate = $request->all();
        
        DB::beginTransaction();

        $user = Users::findOrFail($id);
        
        Users::where(['id' => $id])
                ->update([
                    'name' => $dadosUpdate['nome'],
                    'email' => $dadosUpdate['email'],
                    'password' => isset($dadosUpdate['password']) ? Hash::make($dadosUpdate['password']) : '',
                    'apelido' => $dadosUpdate['apelido'],
                    'foto' => ($request->has('foto')) ? $request->foto : null,
                    'ativo' => $dadosUpdate['status'],
                    'idperfil' => $dadosUpdate['perfil']
                ]);

        /**
         *  Usuário cadastrado tenha perfil Doctor Service.
         */
        if($dadosUpdate['modulo'] == 1){
            $this->updatePessoaFisicaDoctorService($dadosUpdate, $user->id);
        }

        /**
         *  Usuário cadastrado tenha perfil de Operadora.
         */
        if($dadosUpdate['modulo'] == 2){
            $this->updatePessoaFisicaOperador($dadosUpdate, $user->id);
        }

        /**
         *  Usuário cadastrado tenha perfil de Médico.
         */
        if($dadosUpdate['modulo'] == 3){
            $this->updatePessoaFisicaMedico($dadosUpdate, $user->id);
        }

        DB::commit();

        return redirect()->route('usuario.listar');
    }

    private function remover($id)
    {
        $usuario = Users::findOrFail($id);
        $usuario->ativo = 'E';
        $usuario->save();
    }

    private function inativar($id)
    {
        $usuario = Users::findOrFail($id);
        $usuario->ativo = 'I';
        $usuario->save();
    }

    private function ativar($id)
    {
        $usuario = Users::findOrFail($id);
        $usuario->ativo = 'A';
        $usuario->save();
    }
}
