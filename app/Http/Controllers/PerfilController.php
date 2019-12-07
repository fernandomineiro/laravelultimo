<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Perfil;
use App\Models\Acesso;
use App\Models\PerfilAcesso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
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
        $perfil = new Perfil();
        $modulos = Modulo::where('ativo', '=', 'A')
                    ->where('nome', '!=', 'Geral')
                    ->get();
        return view('doctorservice.perfil.cadastro')
            ->with('perfil', $perfil)
            ->with('modulos', $modulos);
    }


    public function getAcessos($idmodulo)
    {
        $acessos = Acesso::where('idmodulo', '=', $idmodulo)->get();
    }

    public function listar(Request $request)
    {
        $filtro = $request->input('filtro', '');

        if ($request->input('chkPerfil')) {
            if ($request->input('acao') == 'Ativar') {

                foreach ($request->input('chkPerfil') as $id) {
                    $this->ativar($id);
                }
            } else if ($request->input('acao') == 'Inativar') {

                foreach ($request->input('chkPerfil') as $id) {
                    $this->inativar($id);
                }
            } else {
                foreach ($request->input('chkPerfil') as $id) {
                    $this->remover($id);
                }
            }
        }
        $perfil = Perfil::select('perfil.id',
                                 'modulo.nome as modulo', 
                                 'perfil.nome', 
                                 'perfil.ativo')
                                 ->join('modulo', 'perfil.idmodulo', 'modulo.id')
                                 ->orderBy('perfil.id', 'asc')
                                 ->where('perfil.ativo', '!=', 'E')
                                 ->when($filtro, function($query) use ($filtro) { 

                                    if($filtro == 'Ativo'){
                                        $filtro = 'A';
                                    } else if($filtro == 'Inativo'){
                                        $filtro = 'I';
                                    }

                                    $query->where(function($query) use ($filtro){
                                        $query->where('modulo.nome', 'like', '%' . $filtro . '%');
                                        $query->orWhere('perfil.nome', 'like', '%' . $filtro . '%');
                                        $query->orWhere('perfil.ativo', '=', $filtro);
                                    });
                                    
                                 })
                                 ->get();
        
        if (Auth::user()->hasAcesso("Perfil")) {
            return view('doctorservice.perfil.pesquisa')
                ->with('perfil', $perfil);
        } else {
            return redirect('/home')
                    ->with('error', 'Você não tem permissão para acessar a tela de Perfil!');
        }
    }

    public function buscarPorModulo($id)
    {
        $telas = Acesso::select('id', 'acesso', 'ativo', 'idmodulo')
                    ->where('idmodulo', '=', $id)
                    ->get();
        return response()->json($telas);
    }

    public function cadastrar(Request $request)
    {
        $this->validate($request, [
            'modulo' => 'required',
            'nome_perfil' => 'required|string|max:45'
        ]);

        $dados = $request->all();
        
        $perfil = new Perfil();
        $perfil->nome = $dados['nome_perfil'];
        $perfil->ativo = $dados['status'];
        $perfil->idmodulo = $dados['modulo'];
        $perfil->save();

        foreach ($dados['id_acesso'] as $acesso) {
            $perfilAcesso = new PerfilAcesso();
            $perfilAcesso->visualizacao = 0;
            $perfilAcesso->cadastro = 0;
            $perfilAcesso->edicao = 0;
            $perfilAcesso->exclusao = 0;
            $perfilAcesso->idacesso = $acesso;
            $perfilAcesso->idperfil = $perfil->id;
            $perfilAcesso->ativo = 'I';
            $perfilAcesso->save();
        }

        if (isset($dados['id_acesso_acessar'])) {
            foreach ($dados['id_acesso_acessar'] as $acessar) {
                PerfilAcesso::where(['idperfil' => $perfil->id, 'idacesso' => $acessar])
                    ->update([
                        'visualizacao' => 1,
                        'ativo' => 'A'
                    ]);
            }
        }

        if (isset($dados['id_acesso_adicionar'])) {
            foreach ($dados['id_acesso_adicionar'] as $adicionar) {
                PerfilAcesso::where(['idperfil' => $perfil->id, 'idacesso' => $adicionar])
                    ->update([
                        'cadastro' => 1
                    ]);
            }
        }

        if (isset($dados['id_acesso_atualizar'])) {
            foreach ($dados['id_acesso_atualizar'] as $atualizar) {
                PerfilAcesso::where(['idperfil' => $perfil->id, 'idacesso' => $atualizar])
                    ->update([
                        'edicao' => 1
                    ]);
            }
        }

        if (isset($dados['id_acesso_remover'])) {
            foreach ($dados['id_acesso_remover'] as $remover) {
                PerfilAcesso::where(['idperfil' => $perfil->id, 'idacesso' => $remover])
                    ->update([
                        'exclusao' => 1
                    ]);
            }
        }     
        
        return redirect()->route('perfil.listar');
    }

    public function getPerfilAcesso($idperfil)
    {
        $perfilAcesso = DB::connection('mysql')->select("SELECT Idacesso,
                                                            acesso,
                                                            SUM(IdPerfil) as IdPerfil,
                                                            SUM(Visualizacao) as Visualizacao,
                                                            SUM(Cadastro) as Cadastro,
                                                            SUM(Edicao) as Edicao,
                                                            SUM(Exclusao) as Exclusao
                                                        FROM
                                                        (SELECT ac.id as Idacesso, 
                                                            ac.acesso as acesso,
                                                            0 as IdPerfil,
                                                            0 as Visualizacao,
                                                            0 as Cadastro,
                                                            0 as Edicao,
                                                            0 as Exclusao
                                                        FROM acesso ac
                                                        inner join modulo m2
                                                            on ac.idmodulo = m2.id
                                                        inner join perfil pf
                                                            on pf.idmodulo = m2.id
                                                        where pf.id = $idperfil
                                                        
                                                        UNION ALL
                                                        
                                                        Select ac.id as Idacesso, 
                                                            ac.acesso as Acesso,
                                                            pa.idperfil as IdPerfil,
                                                            pa.visualizacao as Visualizacao,
                                                            pa.cadastro as Cadastro,
                                                            pa.edicao as Edicao,
                                                            pa.exclusao as Exclusao
                                                        from acesso ac
                                                        inner join modulo m2
                                                            on ac.idmodulo = m2.id
                                                        inner join perfil pf
                                                            on pf.idmodulo = m2.id
                                                        inner join perfil_acesso pa 
                                                            on pa.idacesso = ac.id and pa.idperfil = pf.id
                                                        where pf.id = $idperfil)x group by IDacesso, Acesso");
        
        return response()->json($perfilAcesso);
    }

    public function editar($id)
    {
        $perfil = Perfil::find($id);
        $modulos = Modulo::where('ativo', '=', 'A')
                    ->where('nome', '!=', 'Geral')
                    ->get();
        
        return view('doctorservice.perfil.cadastro')
            ->with('perfil', $perfil)
            ->with('modulos', $modulos);        
    }

    public function atualizar(Request $request, $id)
    {
        $this->validate($request, [
            'modulo' => 'required',
            'nome_perfil' => 'required|string|max:45'
        ]);

        $dadosUpdate = $request->post();

        Perfil::where(['id' => $id])
            ->update([
                'idmodulo' => $dadosUpdate['modulo'],
                'nome' => $dadosUpdate['nome_perfil'],
                'ativo' => $dadosUpdate['status']
            ]);        

        $perfil = Perfil::find($id);

        foreach ($dadosUpdate['id_acesso'] as $acesso) {
            PerfilAcesso::updateOrCreate(
                ['idperfil' => $id, 'idacesso' => $acesso],
                [
                    'visualizacao' => 0,
                    'cadastro' => 0,
                    'edicao' => 0,
                    'exclusao' => 0,
                    'idacesso' => $acesso,
                    'idperfil' => $perfil->id,
                    'ativo' => 'I'
                ]);
        }

        if(isset($dadosUpdate['id_acesso_acessar'])){
            foreach ($dadosUpdate['id_acesso_acessar'] as $acessar) {
                PerfilAcesso::updateOrCreate(
                    ['idperfil' => $id, 'idacesso' => $acessar],
                    [
                        'visualizacao' => 1,
                        'ativo' => 'A'
                    ]);
            }
        }
        
        if(isset($dadosUpdate['id_acesso_adicionar'])){
            foreach ($dadosUpdate['id_acesso_adicionar'] as $adicionar) {
                PerfilAcesso::updateOrCreate(
                    ['idperfil' => $id, 'idacesso' => $adicionar],
                    ['cadastro' => 1]
                );
            }
        }
        
        if (isset($dadosUpdate['id_acesso_atualizar'])) {
            foreach ($dadosUpdate['id_acesso_atualizar'] as $atualizar) {
                PerfilAcesso::updateOrCreate(
                    ['idperfil' => $id, 'idacesso' => $atualizar],
                    ['edicao' => 1]
                );
            }
        }
        
        if (isset($dadosUpdate['id_acesso_remover'])) {
            foreach ($dadosUpdate['id_acesso_remover'] as $remover) {
                PerfilAcesso::updateOrCreate(
                    ['idperfil' => $id, 'idacesso' => $remover],
                    ['exclusao' => 1]
                );
            }
        }        

        return redirect()->route('perfil.listar');
    }

    private function remover($id)
    {
        $perfil = Perfil::find($id);
        $perfil->ativo = 'E';
        $perfil->save();
    }

    private function inativar($id)
    {
        $perfil = Perfil::find($id);
        $perfil->ativo = 'I';
        $perfil->save();
    }

    private function ativar($id)
    {
        $perfil = Perfil::find($id);
        $perfil->ativo = 'A';
        $perfil->save();
    }
}
