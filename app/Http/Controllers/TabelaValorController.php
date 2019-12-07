<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\OperadoraUnidade;
use App\Models\TabelaValor;
use App\Models\Convenio;
use App\Models\Especialidade;
use App\Models\Valor;

class TabelaValorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = \Auth::user()->id;

        $unidades = OperadoraUnidade::select('operadora_unidade.id', 'operadora_unidade.nome', 'operador.idoperadora')
            ->join('operador', 'operadora_unidade.idoperadora', 'operador.idoperadora')
            ->where('operador.user_id', $userId)
            ->where('operadora_unidade.ativo', 'A')
            ->get();

        return view('tabela-valor.cadastro', [
                'unidades' => $unidades
            ]);
    }

    public function getUnidades()
    {
        $unidades = OperadoraUnidade::select('operadora_unidade.id', 'operadora_unidade.nome', 'operador.idoperadora')
            ->join('operador', 'operadora_unidade.idoperadora', 'operador.idoperadora')
            ->where('operador.user_id', \Auth::user()->id)
            ->where('operadora_unidade.ativo', 'A')
            ->get();

        return response()->json($unidades);
    }

    public function listar(Request $request)
    {
        $filtro = $request->input('filtro', '');        

        if($request->input('chkTabela')){
            if($request->input('acao') == 'Ativar'){

                foreach($request->input('chkTabela') as $id){
                    $this->ativar($id);
                }
            }else if($request->input('acao') == 'Inativar'){

                foreach($request->input('chkTabela') as $id){
                    $this->inativar($id);
                }
            }else{

                foreach($request->input('chkTabela') as $id){
                    $this->remover($id);
                }
            }
        }

        $tabelas = TabelaValor::select('tabela_valor.id', 
                                       'operadora_unidade.nome as unidade', 
                                       'tabela_valor.nome as nome', 'expira', 
                                       'tabela_valor.status as status')
            ->leftJoin('operadora_unidade', 'tabela_valor.idoperadora_unidade', 'operadora_unidade.id')
            ->where('tabela_valor.status', '!=', 'E')
            ->orderBy('tabela_valor.id', 'asc')
            ->when($filtro, function ($query) use ($filtro) {
                if ($filtro == 'Ativo') {
                    $filtro = 'A';
                } else if ($filtro == 'Inativo') {
                    $filtro = 'I';
                }
                $query->where(function ($query) use ($filtro) {
                    $query->orwhere('operadora_unidade.nome', 'like', '%' . $filtro . '%');
                    $query->orWhere('tabela_valor.nome', 'like', '%' . $filtro . '%');
                    $query->orWhereDate('expira', date('Y-m-d', strtotime(str_replace("/", "-", $filtro))));
                    $query->orWhereDay('expira', $filtro);
                    $query->orWhereMonth('expira', $filtro);
                    $query->orWhereYear('expira', $filtro);
                    $query->orWhere('tabela_valor.status', 'like', '%' . $filtro . '%');
                });
            })
            ->get();

        if (Auth::user()->hasAcesso("Tabela de Valores")) {
            return view('tabela-valor.pesquisa', ['tabelas'=>$tabelas]);
        } else {
            return redirect()->route('home')
                    ->with('error', 'Você não tem permissão para acessar a tela de Tabelas de Valores!');
        }
    }

    public function cadastrar(Request $request)
    {        
        // Existe uma tabela geral ativa?
        $existeTabelaGeralAtiva = TabelaValor::where('status', 'A')->whereNull('idoperadora_unidade')->exists();
        
        if($request->unidade != null){
            $existeUnidadeAtiva = TabelaValor::where('idoperadora_unidade', $request->unidade)->where('status', 'A')->exists();
        }

        $this->validate($request, [
            'nome' => 'required|max:100',
            'descricao' => 'required|max:400',
            'expira_em' => 'date_format:d/m/Y H:i',
            'status' => 'in:A,I'
        ]);
        
        if($existeTabelaGeralAtiva && $request->status == 'A') {
             return redirect()->route('tabela-valor')
                ->with('error', 'Desculpe, mas já existe uma tabela de valores ativa para esta operadora.');
        }

        if($existeUnidadeAtiva) {
            return redirect()->route('tabela-valor')
               ->with('error', 'Já existe uma tabela de valor ativa para esta unidade.');
        }        
        
        $id = $this->popularDadosCadastro($request);
        
        return redirect()->route('tabela-valor.editar', $id);

    }

    private function popularDadosCadastro(Request $request){
        $id = TabelaValor::insertGetId([
            'idoperadora_unidade' => $request->unidade,
            'idoperadora' => $request->idoperadora,
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'expira' => isset($request->expira_em) ? date('Y-m-d H:i', strtotime(str_replace("/", "-", $request->expira_em))) : null,
            'status' => $request->status
        ]);
        return $id;
    }

    public function editar($id)
    {
        $userId = \Auth::user()->id;
        $tabela = TabelaValor::findOrFail($id);
        $convenios = Convenio::where('ativo', 'A')->get();
        $especialidades = Especialidade::where('ativo', 'A')->get();
        $unidades = OperadoraUnidade::select('operadora_unidade.id', 'operadora_unidade.nome', 'operador.idoperadora')
            ->join('operador', 'operadora_unidade.idoperadora', 'operador.idoperadora')
            ->where('operador.user_id', $userId)
            ->where('operadora_unidade.ativo', 'A')
            ->get();

        $valores = Valor::select('valor.id', 
            'convenio.nome as convenio', 
            'especialidade.nome as especialidade', 
            'valor_rpa', 
            'valor_clt', 
            'valor_pj')
            ->join('convenio', 'valor.idconvenio', 'convenio.id')
            ->join('especialidade', 'valor.idespecialidade', 'especialidade.id')
            ->where('valor.ativo', 'A')
            ->where('valor.idtabela_valor', $tabela->id)
            ->get();

        return view('tabela-valor.cadastro', [
            'tabela' => $tabela,
            'unidades' => $unidades,
            'idoperadora' => $tabela->idoperadora,
            'convenios' => $convenios,
            'especialidades' => $especialidades,
            'valores' => $valores
        ]);
    }

    public function atualizar(Request $request, $id)
    {
        $this->validate($request, [
            'nome' => 'required|max:100',
            'descricao' => 'required|max:400',
            'expira_em' => 'date_format:d/m/Y H:i',
            'status' => 'in:A,I'
        ]);

        if($request->input('remover')){

            $tabela = TabelaValor::findOrFail($id);
            if ($tabela->status != 'E'){
                $tabela->status = 'E';
                $tabela->save();
            }
            return redirect()->route('tabela-valor.listar');

        }

        $tabelaSalva = TabelaValor::findOrFail($id);

        $existeTabelasGeralAtivas = TabelaValor::whereNull('idoperadora_unidade')
                                ->where('idoperadora', $request->idoperadora)
                                ->where('status', 'A')
                                ->exists();

        $existeUnidadeAtiva = TabelaValor::whereNotNull('idoperadora_unidade')
                                ->where('idoperadora_unidade', $tabelaSalva->idoperadora_unidade)
                                ->where('idoperadora', $request->idoperadora)
                                ->where('status', 'A')
                                ->exists();
        
        if($tabelaSalva->idoperadora_unidade != null && $existeTabelasGeralAtivas){
            return redirect()->route('tabela-valor.editar', $id)
               ->with('error', 'Desculpe, mas já existe uma tabela de valor ativa para a sua operadora.');
        }

        // iNATIVA AS TABELAS DA OPERADORA E DAS UNIDADES CASO ESTEJAM ATIVAS.
        if($tabelaSalva->idoperadora_unidade == null && !$existeTabelasGeralAtivas){
            TabelaValor::where('idoperadora', $request->idoperadora)
                ->where('status', '=', 'A')
                ->update(['status' => 'I']);
        }

        if($existeUnidadeAtiva){
            return redirect()->route('tabela-valor.editar', $id)
               ->with('error', 'Sinto muito, mas já existe uma tabela de valor ativa para esta unidade.');
        }

        // INATIVA AS TABELAS DE UMA DETERMINADA UNIDADE CASO ESTEJAM ATIVAS.
        if($tabelaSalva->idoperadora_unidade != null && !$existeUnidadeAtiva){
            TabelaValor::where('idoperadora_unidade', $tabelaSalva->idoperadora_unidade)
                ->where('idoperadora', $request->idoperadora)
                ->where('status', '=', 'A')
                ->update(['status' => 'I']);
        }

        TabelaValor::where(['id' => $id])
            ->update([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'expira' => isset($request->expira_em) ? date('Y-m-d H:i', strtotime(str_replace("/", "-", $request->expira_em))) : null,
                'status' => $request->status,
                'idoperadora' => $request->idoperadora
            ]);


        return redirect()->route('tabela-valor.listar');
    }

    public function listarValores(Request $request)
    {
        $mensagem = '';

        if($request->acao == 'adicionar'){
            $status = $this->adicionarValores($request);
            if($status){
                $mensagem = "Valores cadastrados com sucesso.";
            } else {
                $mensagem = "Já existem valores cadastrados para este convênio e especialidade!";
            }
        }

        if($request->acao == 'alterar'){
            $status = $this->alterarValores($request);
            if($status){
                $mensagem = "Valores alterados com sucesso.";
            }
        }

        if($request->acao == 'remover'){
           $status = $this->removerValores($request);
           if($status){
                $mensagem = "Valores removidos com sucesso.";
            } else {
                $mensagem = "Não há registros dos valores informados para remover.";
            }
        }

        if($status){
            return redirect()->route('tabela-valor.editar', $request->idtabela_valor)
                    ->with('success', $mensagem);
        } else {
            return redirect()->route('tabela-valor.editar', $request->idtabela_valor)
                    ->with('error', $mensagem);
        }
        
    }

    public function clonarTabela($id)
    {
        $tabela = TabelaValor::findOrFail($id);
        $valores = Valor::where('idtabela_valor', $id)
            ->select('idtabela_valor', 
                'idconvenio',
                'idespecialidade',
                'valor_rpa',
                'valor_clt',
                'valor_pj')
            ->get();
            

        $id_tabela_clonada = TabelaValor::insertGetId([
            'idoperadora_unidade' => $tabela->idoperadora_unidade,
            'idoperadora' => $tabela->idoperadora,
            'nome' => $tabela->nome .= " (CÓPIA)",
            'descricao' => $tabela->descricao,
            'expira' => isset($tabela->expira) ? $tabela->expira : null,
            'status' => 'I'
        ]);

        if(isset($valores) && !empty($valores)){

            foreach($valores as $valor) {
                
                $valores_a_clonar = new Valor();
                $valores_a_clonar->idtabela_valor = $id_tabela_clonada;
                $valores_a_clonar->idconvenio = $valor->idconvenio;
                $valores_a_clonar->idespecialidade = $valor->idespecialidade;
                $valores_a_clonar->valor_rpa = $valor->valor_rpa;
                $valores_a_clonar->valor_clt = $valor->valor_clt;
                $valores_a_clonar->valor_pj = $valor->valor_pj;
                $valores_a_clonar->ativo = 'A';
                $valores_a_clonar->save();
            }

        }

        return redirect()->route('tabela-valor.editar', $id_tabela_clonada);
    }

    private function adicionarValores($request)
    {
        // dd($request->all());
        $this->validate($request, [
            'convenio' => 'required',
            'especialidade' => 'required',
            'valor_rpa' => 'nullable|regex:/^[0-9]{3},[0-9]{2}$/',
            'valor_clt' => 'nullable|regex:/^[0-9]{3},[0-9]{2}$/',
            'valor_pj' => 'nullable|regex:/^[0-9]{3},[0-9]{2}$/'
        ]);

        $existeValor = Valor::where(['idconvenio' => $request->convenio, 'idespecialidade' => $request->especialidade, 'idtabela_valor' => $request->idtabela_valor])->exists();
        
        if($existeValor == false){
            Valor::firstOrCreate(['idconvenio' => $request->convenio,
                'idespecialidade' => $request->especialidade,
                'idtabela_valor' => $request->idtabela_valor], [
                    'idtabela_valor' => $request->idtabela_valor,
                    'idconvenio' => $request->convenio,
                    'idespecialidade' => $request->especialidade,
                    'valor_rpa' => isset($request->valor_rpa) ? str_replace(',', '.', $request->valor_rpa) : null,
                    'valor_clt' => isset($request->valor_clt) ? str_replace(',', '.', $request->valor_clt) : null,
                    'valor_pj' => isset($request->valor_pj) ? str_replace(',', '.', $request->valor_pj) : null,
                    'ativo' => 'A'
                ]);
            return true;
        }
        else {
            return false;
        }

    }

    private function alterarValores($request)
    {
        
        $this->validate($request, [
            'convenio' => 'required',
            'especialidade' => 'required',
            'valor_rpa' => 'regex:/^[0-9]{2},[0-9]{2}$/',
            'valor_clt' => 'regex:/^[0-9]{2},[0-9]{2}$/',
            'valor_pj' => 'regex:/^[0-9]{2},[0-9]{2}$/'
        ]);

        Valor::where([
            'idconvenio' => $request->convenio,
            'idespecialidade' => $request->especialidade,
            'idtabela_valor' => $request->idtabela_valor,
        ])->update([
            'valor_rpa' => isset($request->valor_rpa) ? str_replace(",", ".", $request->valor_rpa) : null,
            'valor_clt' => isset($request->valor_clt) ? str_replace(",", ".", $request->valor_clt) : null,
            'valor_pj' => isset($request->valor_pj) ? str_replace(",", ".", $request->valor_pj) : null,
        ]);
            
        return true;
    }

    private function removerValores($request)
    {
        
        $this->validate($request, [
            'convenio' => 'required',
            'especialidade' => 'required',
            'valor_rpa' => 'regex:/^[0-9]{2},[0-9]{2}$/',
            'valor_clt' => 'regex:/^[0-9]{2},[0-9]{2}$/',
            'valor_pj' => 'regex:/^[0-9]{2},[0-9]{2}$/'
        ]);

        $existeValor = Valor::where(['idconvenio' => $request->convenio, 'idespecialidade' => $request->especialidade, 'idtabela_valor' => $request->idtabela_valor])->exists();
        
        if($existeValor){
            Valor::where([
                'idconvenio' => $request->convenio,
                'idespecialidade' => $request->especialidade,
                'idtabela_valor' => $request->idtabela_valor,
            ])->delete();
    
            return true;
        }
        else {
            return false;
        }
    }

    public function buscarValor(Request $request)
    {
        $convenio = $request->query->get('convenio');
        $especialidade = $request->query->get('especialidade');
        $idtabela = $request->query->get('idtabela');

        $valor = Valor::select('convenio.nome as convenio',
                                'especialidade.nome as especialidade',
                                'valor.valor_rpa',
                                'valor.valor_clt',
                                'valor.valor_pj')                        
                        ->join('convenio', 'valor.idconvenio', 'convenio.id')
                        ->join('especialidade', 'valor.idespecialidade', 'especialidade.id')
                        ->where('idconvenio', $convenio)
                        ->where('idespecialidade', $especialidade)
                        ->where('idtabela_valor', $idtabela)
                        ->where('valor.ativo', 'A')
                        ->get();

        return response()->json($valor);
    }
    private function remover($id)
    {
        $tabela = TabelaValor::findOrFail($id);
        $tabela->status = 'E';
        $tabela->save();
    }

    private function inativar($id)
    {
        $tabela = TabelaValor::findOrFail($id);
        $tabela->status = 'I';
        $tabela->save();
    }

    private function ativar($id)
    {
        $tabela = TabelaValor::findOrFail($id);
        if($tabela->idoperadora_unidade == null){ // Se for Geral
            $existeTabelasAtivas = TabelaValor::where('status', 'A')->exists();
            if($existeTabelasAtivas){
                TabelaValor::where('status', 'A')->update(['status' => 'I']);
            }
            $tabela->status = 'A';
            $tabela->save();
        }

        if($tabela->idoperadora_unidade != null){ // Se for uma unidade específica.
            $existeUnidadeAtiva = TabelaValor::where('idoperadora_unidade', $tabela->idoperadora_unidade)
                                    ->where('status', 'A')
                                    ->exists();
            $existeTabelaGeralAtiva = TabelaValor::whereNull('idoperadora_unidade')->where('status', 'A')->exists();
            if($existeUnidadeAtiva){
                TabelaValor::where('idoperadora_unidade', $tabela->idoperadora_unidade)
                    ->where('status', 'A')
                    ->update(['status' => 'I']);
            }
            if($existeTabelaGeralAtiva){
                return redirect()->route('tabela-valor.listar')
                    ->with('error', 'Desculpe, mas já existe uma tabela de valor ativa para a sua operadora.');
            }
            $tabela->status = 'A';
            $tabela->save();
        }
    }
}