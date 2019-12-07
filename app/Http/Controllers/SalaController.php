<?php


namespace App\Http\Controllers;

use App\User;
use App\Models\Sala;
use App\Models\Especialidade;
use App\Models\SalaEspecialidade;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscarPorUnidade($id)
    {
        return response()->json([
            'salas' => Sala::where(['idoperadora_unidade' => $id])->orderBy('nome')->get()
        ]);
    }

    public function buscarUnidades()
    {
        return User::select('operadora_unidade.id', 'operadora_unidade.nome')
                    ->join('operador', 'users.id', 'operador.user_id')
                    ->join('operadora_unidade', 'operador.idoperadora', 'operadora_unidade.idoperadora')
                    ->where('users.id', Auth::user()->id)
                    ->get();
    }

    public function buscarEspecialidades()
    {
        return Especialidade::where('ativo', 'A')->get();
    }

    public function index()
    {
        $unidades = $this->buscarUnidades();
        $especialidades = $this->buscarEspecialidades();
        return view('sala.cadastro', [
            'unidades' => $unidades,
            'especialidades' => $especialidades,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'unidade' => 'required',
            'especialidade' => 'required',
            'nome' => 'required|max:100',
            'descricao' => 'nullable|max:400',
            'cor' => 'required|max:7',
        ]);

        DB::beginTransaction();

        $sala = new Sala();
        $sala->idoperadora_unidade = $request->unidade;
        $sala->nome = $request->nome;
        $sala->descricao = $request->descricao;
        $sala->cor_rgb = $request->cor;
        $sala->ativo = $request->status;
        
        if(!$sala->save()){
            DB::rollBack();
            return false;
        }

        $salaEspecialidade = new SalaEspecialidade();
        $salaEspecialidade->idsala = $sala->id;
        $salaEspecialidade->idespecialidade = $request->especialidade;

        if(!$salaEspecialidade->save()){
            DB::rollBack();
            return false;
        }

        DB::commit();

        return redirect()->route('sala.listar');
    }

    public function listar(Request $request)
    {
        $filtro = $request->input('filtro','');
          
        if($request->input('chkSala')){

            if($request->input('acao') == 'Ativar') {

                foreach($request->input('chkSala') as $id) {
                    $this->ativar($id);
                }

            } else if($request->input('acao') == 'Inativar') {

                foreach($request->input('chkSala') as $id) {
                    $this->inativar($id);
                }

            } else {

                foreach($request->input('chkSala') as $id){
                    $this->remover($id);
                }
            }
        }

        $unidades = $this->buscarUnidades();
        $salas = User::select('sala.id', 
                            'operadora_unidade.nome as unidade', 
                            'sala.nome as sala', 
                            'sala.cor_rgb as cor', 
                            'sala.ativo')
                    ->join('operador', 'users.id', 'operador.user_id')
                    ->join('operadora_unidade', 'operador.idoperadora', 'operadora_unidade.idoperadora')
                    ->join('sala', 'operadora_unidade.id', 'sala.idoperadora_unidade')
                    ->where('users.id', Auth::user()->id)
                    ->where('sala.ativo', '<>', 'E')
                    ->when($filtro, function($query) use ($filtro){
                        if ($filtro == 'Ativo') {
                            $filtro = 'A';
                        } else if ($filtro == 'Inativo') {
                            $filtro = 'I';
                        }
                        $query->where(function ($query) use ($filtro) {
                            $query->orWhere('operadora_unidade.nome', 'like', '%' . $filtro . '%');
                            $query->orWhere('sala.nome', 'like', '%' . $filtro . '%');
                            $query->orWhere('sala.ativo', '=', $filtro);
                        });
                    })
                    ->orderBy('sala.id')
                    ->get();

        if(Auth::user()->hasAcesso("Sala")){

            return view('sala.pesquisa', [
                'salas' => $salas,
                'unidades' => $unidades
            ]);

        } else {

            return redirect()->route('home')
                    ->with('error', 'Você não tem permissão para acessar a tela de Sala!');

        }
    }

    public function edit($id)
    {
        $unidades = $this->buscarUnidades();
        $especialidades = $this->buscarEspecialidades();
        $sala = Sala::select('sala.id', 'idoperadora_unidade', 'sala.nome', 'sala.descricao', 'cor_rgb', 'sala.ativo', 'sala_especialidade.idespecialidade')
            ->join('sala_especialidade', 'sala.id', 'sala_especialidade.idsala')
            ->where('sala.id', $id)
            ->first();

        return view('sala.editar', [
            'sala' => $sala,
            'unidades' => $unidades,
            'especialidades' => $especialidades
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $this->validate($request, [
            'unidade' => 'required',
            'especialidade' => 'required',
            'nome' => 'required|max:100',
            'descricao' => 'nullable|max:400',
            'cor' => 'required|max:7',
        ]);

        if($request->input('remover')){
            $sala = Sala::findOrFail($id);
            if ($sala->ativo != 'E'){
                $sala->ativo = 'E';
                $sala->save();
            }
            return redirect()->route('sala.listar');
        }

        Sala::where(['id' => $id])
            ->update([
                'idoperadora_unidade' => $request->unidade,
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'cor_rgb' => $request->cor,
                'ativo' => $request->status
            ]);

        SalaEspecialidade::updateOrCreate(
            ['idsala' => $id],
            ['idsala' => $id, 'idespecialidade' => $request->especialidade]
        );

        return redirect()->route('sala.listar');
    }

    private function remover($id) {
        $sala = Sala::find($id);
        $sala->ativo = 'E';
        $sala->save();
    }

    private function inativar($id) {
        $sala = Sala::find($id);
        $sala->ativo = 'I';
        $sala->save();
    }

    private function ativar($id) {
        $sala = Sala::find($id);
        $sala->ativo = 'A';
        $sala->save();
    }
}