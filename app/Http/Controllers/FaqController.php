<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modulo;
use App\Models\Faq;
use App\User;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $modulos = Modulo::all();;
        return view('doctorservice.faq.cadastro')
            ->with('modulos', $modulos);
    }

    public function listarPerguntas()
    {
        $faqGeral = Faq::whereNull('idmodulo')
                                ->orderBy('ordem', 'asc')
                                ->get();

        $moduloDoUsuário = Modulo::select('modulo.nome')
                            ->join('perfil', 'modulo.id', 'perfil.idmodulo')
                            ->join('users', 'perfil.id', 'users.idperfil')
                            ->where('users.id', Auth::user()->id)
                            ->first();
        
        $perguntasDoModulo = User::select('faq.id',
                                'faq.questao',
                                'faq.resposta',
                                'faq.ordem')
                            ->join('perfil', 'users.idperfil', 'perfil.id')
                            ->join('modulo', 'perfil.idmodulo', 'modulo.id')
                            ->join('faq', 'faq.idmodulo', 'modulo.id')
                            ->where('users.id', '=', Auth::user()->id)
                            ->orderBy('ordem', 'asc')
                            ->get();
        
        if (Auth::user()->hasAcesso("Apresentação de FAQ")) {
            return view('informacoes.faq.faq')
                ->with('gerais', $faqGeral)
                ->with('perguntas', $perguntasDoModulo)
                ->with('modulo', $moduloDoUsuário);
        } else {
            return redirect('/home')
                    ->with('error', 'Você não tem permissão para acessar a tela de Apresentação de FAQ!');
        }
    }

    public function getModulos()
    {
        $modulos = Modulo::all();
        return response()->json($modulos);
    }

    public function up($id)
    {
        $faqClicada = Faq::select('id', 'ordem', 'idmodulo')
                        ->where('id', $id)
                        ->first();

        $faqAnterior = Faq::where('ordem', '=', $faqClicada->ordem - 1)
                        ->where('idmodulo', $faqClicada->idmodulo)
                        ->first();
        
        if($faqClicada->ordem > $faqAnterior->ordem) {
            Faq::where(['id' => $faqAnterior->id, 'idmodulo' => $faqAnterior->idmodulo])
                ->increment('ordem');

            Faq::where(['id' => $faqClicada->id, 'idmodulo' => $faqClicada->idmodulo])
                ->decrement('ordem');            
        }
    }
    
    public function down($id)
    {
        $faqClicada = Faq::select('id', 'ordem', 'idmodulo')
                        ->where('id', $id)
                        ->first();

        $faqPosterior = Faq::where('ordem', '=', $faqClicada->ordem + 1)
                        ->where('idmodulo', $faqClicada->idmodulo)
                        ->first();

        if ($faqClicada->ordem < $faqPosterior->ordem) {
            Faq::where(['id' => $faqPosterior->id, 'idmodulo' => $faqPosterior->idmodulo])
                ->decrement('ordem');

            Faq::where(['id' => $faqClicada->id, 'idmodulo' => $faqClicada->idmodulo])
                ->increment('ordem');
        }
    }

    public function listar(Request $request)
    {
        $filtro = $request->input('filtro', '');
    
        if ($request->input('chkFaq')) {
            if ($request->input('acao') == 'Ativar') {
                foreach ($request->input('chkFaq') as $id) {
                    $this->ativar($id);
                }
            } else if ($request->input('acao') == 'Inativar') {
                foreach ($request->input('chkFaq') as $id) {
                    $this->inativar($id);
                }
            } else {
                foreach ($request->input('chkFaq') as $id) {
                    $this->remover($id);
                }
            }
        }
        
        $faqs = Faq::select(
                    'faq.id as id', 
                    'modulo.nome as modulo',
                    'faq.questao as pergunta',
                    'faq.ativo',
                    'faq.ordem as ordem')
                    ->leftJoin('modulo', 'faq.idmodulo', 'modulo.id')
                    ->when($filtro, function ($query) use ($filtro) {
                        if ($filtro == 'Ativo') {
                            $filtro = 'A';
                        } else if ($filtro == 'Inativo') {
                            $filtro = 'I';
                        }
                        $query->where(function ($query) use ($filtro) {
                            $query->orWhere('questao', 'like', '%' . $filtro . '%');
                            $query->orWhere('modulo.nome', 'like', '%' . $filtro . '%');
                            $query->orWhere('faq.ativo', '=', $filtro);
                        });
                    })
                    ->where('faq.ativo', '<>', 'E')
                    ->orderBy('idmodulo', 'asc')
                    ->orderBy('ordem', 'asc')
                    ->get();

        if (Auth::user()->hasAcesso("FAQ")) {
            return view('doctorservice.faq.pesquisa')
                ->with('faqs', $faqs);
        } else {
            return redirect('/home')
                ->with('error', 'Você não tem permissão para acessar a tela de FAQ!');
        }
    }

    public function cadastrar(Request $request)
    {
        $this->validate($request, [
            'pergunta' => 'required',
            'resposta' => 'required|max:1900'
        ]);

        $dados = $request->all();

        $registros = $this->totalDeRegistros($dados['visibilidade']);
        
        $faq = new Faq();
        $faq->idmodulo = $dados['visibilidade'];
        $faq->questao = $dados['pergunta'];
        $faq->resposta = $dados['resposta'];
        $faq->ativo = $dados['status'];

        if($registros >= 0){
            $faq->ordem = ++$registros;
        }

        $faq->save();

        return redirect()->route('faq.listar');
    }

    public function editar($id)
    {
        $faq = Faq::findOrFail($id);
        $modulos = Modulo::where('ativo', '=', 'A')->get();

        return view('doctorservice.faq.editar')
                ->with('faq', $faq)
                ->with('modulos', $modulos);
    }

    public function atualizar(Request $request, $id)
    {
        $this->validate($request, [
            'pergunta' => 'required|max:400',
            'resposta' => 'required|max:2000'
        ]);

        if ($request->input('remover')) {
            $faq = Faq::findOrFail($id);
            if ($faq->ativo != 'E') {
                $faq->ativo = 'E';
                $faq->save();
            }
            return redirect()->route('faq.listar');
        }

        Faq::where(['id' => $id])
            ->update([
                'idmodulo' => $request->visibilidade,
                'questao' => $request->pergunta,
                'resposta' => $request->resposta,
                'ativo' => $request->status
            ]);

        return redirect()->route('faq.listar');
    }

    private function totalDeRegistros($modulo)
    {
        $totalDeRegistrosDoModulo = count(Faq::where(['idmodulo' => $modulo])->where('ativo', '<>', 'E')->get());
        return $totalDeRegistrosDoModulo;
    }

    private function remover($id)
    {
        $faq = Faq::find($id);
        $faq->ativo = 'E';
        $faq->save();
    }

    private function inativar($id)
    {
        $faq = Faq::find($id);
        $faq->ativo = 'I';
        $faq->save();
    }

    private function ativar($id)
    {
        $faq = Faq::find($id);
        $faq->ativo = 'A';
        $faq->save();
    }
}
