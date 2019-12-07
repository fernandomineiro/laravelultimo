<?php

namespace App\Http\Controllers;

use App\Models\Operadora;
use App\Models\Plantao;
use App\Models\PlantaoStatus;
use App\Models\VagaRecorrencia;
use App\Models\VagaStatus;
use App\Models\Valor;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use App\Models\Especialidade;
use App\Models\Medico;
use App\Models\ModalidadePagamento;
use App\Models\OperadoraUnidade;
use App\Models\PessoaFisica;
use App\Models\TabelaValor;
use App\Models\TipoContratacao;
use App\Models\Vaga;
use App\Models\VagaModalidadePagamento;
use App\Models\VagaTipoContratacao;
use App\Models\VagaCandidatura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sala;


class VagaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
 
    public function listar(Request $request)
    {

        // retorna operadora do user logado
        $operadora = Users::join('operador AS o', 'o.user_id', 'users.id')
            ->join('operadora AS op', 'op.id', 'o.idoperadora')
            ->where('users.id', '=', Auth::user()->id)
            ->select('op.id AS id')
            ->first();

        if ($request->input('chkBanco')) {
            if ($request->input('acao') == 'Ativar') {

                foreach ($request->input('chkBanco') as $id) {
                    $this->ativar($id);
                }
            } else if ($request->input('acao') == 'Inativar') {

                foreach ($request->input('chkBanco') as $id) {
                    $this->inativar($id);
                }
            } else {

                foreach ($request->input('chkBanco') as $id) {

                    $this->remover($id);
                }
            }
        }


        $vagas = Vaga::select('sala.nome as sala',
            'especialidade.nome as especialidade',
            'operadora_unidade.nome as operadora_unidade',
            //'pessoa_fisica.nome as medico',
            'vaga.*',
            'vaga_status.cor',
            'vaga_status.nome')
            ->join('sala', 'sala.id', 'vaga.idsala')
            ->join('especialidade', 'especialidade.id', 'vaga.idespecialidade')
            ->join('operadora_unidade', 'operadora_unidade.id', 'sala.idoperadora_unidade')
            ->join('vaga_status', 'vaga_status.id', 'vaga.idvaga_status')
            ->when($operadora, function ($query) use ($operadora) {

                $query->where('operadora_unidade.idoperadora', $operadora->id);
            })
            // ->join('medico', 'medico.id', 'vaga.idmedico')
            //->join('pessoa_fisica', 'pessoa_fisica.idpessoa', 'medico.idpessoa')
            ->get();

        return view('vaga.listar', [
            'vagas' => $vagas
        ]);
    }

    public function cadastrar()
    {

        $operadoras = Users::join('operador AS o', 'o.user_id', 'users.id')
            ->join('operadora AS op', 'op.id', 'o.idoperadora')
            ->join('pessoa AS p', 'p.id', 'op.idpessoa')
            ->join('pessoa_juridica AS pj', 'pj.idpessoa', 'p.id')
            ->where('users.id', '=', Auth::user()->id)
            ->select('op.id AS id', 'razao_social AS nome')
            ->first();

        if (isset($operadoras)) {

            $unidades = OperadoraUnidade::where(['ativo' => 'A'])
                ->where('idoperadora', '=', $operadoras->id)
                ->get();

            $medicos = Medico::join('pessoa_fisica', 'pessoa_fisica.idpessoa', 'medico.idpessoa')
                ->join('operadora_grupo_medico', 'operadora_grupo_medico.idmedico', 'medico.id')
                ->join('operadora_grupo', 'operadora_grupo.id', 'operadora_grupo_medico.idoperadora_grupo_medico')
                ->where(['medico.ativo' => 'A', 'operadora_grupo.idoperadora' => $operadoras->id])
                ->selectRaw("medico.id, concat(medico.crm, ' - ', pessoa_fisica.nome) nome")
                ->get();
        } else {

            $unidades = OperadoraUnidade::where(['ativo' => 'A'])
                ->get();

            $medicos = Medico::join('pessoa_fisica', 'pessoa_fisica.idpessoa', 'pessoa_fisica.idpessoa')
                ->where(['medico.ativo' => 'A'])
                ->get();
        }

        $especialidades = Especialidade::where(['ativo' => 'A'])->get();
        $modalidadesPag = ModalidadePagamento::where(['ativo' => 'A'])->get();
        $tabelaPrecos = TabelaValor::where(['status' => 'A'])->get();
        $tipoContratacoes = TipoContratacao::where(['ativo' => 'A'])->get();


        return view('vaga.cadastro', [
            'unidades' => $unidades,
            'especialidades' => $especialidades,
            'medicos' => $medicos,
            'modalidadesPag' => $modalidadesPag,
            'tabelaPrecos' => $tabelaPrecos,
            'tipoContratacoes' => $tipoContratacoes
        ]);
    }

    public function editar($idvaga)
    {
        $vaga = Vaga::where(['id' => $idvaga])->first();

        $sala = Sala::where(['id' => $vaga->idsala])->first();

        $unidade = OperadoraUnidade::where(['id' => $sala->idoperadora_unidade])->first();

        $unidades = OperadoraUnidade::where(['ativo' => 'A'])->get();
        $especialidades = Especialidade::where(['ativo' => 'A'])->get();
        $modalidadesPag = ModalidadePagamento::where(['ativo' => 'A'])->get();
        $tabelaPrecos = TabelaValor::where(['status' => 'A'])->get();
        $tipoContratacoes = TipoContratacao::where(['ativo' => 'A'])->get();

        $tipoContratacao = new VagaTipoContratacao();
        $tipoContratacao->idvaga = $idvaga;

        $modalidadePagamento = new VagaModalidadePagamento();
        $modalidadePagamento->idvaga = $idvaga;

        $vagaRecorrencia = VagaRecorrencia::where(['idvaga' => $idvaga])->first();
        $medicos = Medico::join('pessoa_fisica', 'pessoa_fisica.idpessoa', 'medico.idpessoa')
            ->join('operadora_grupo_medico', 'operadora_grupo_medico.idmedico', 'medico.id')
            ->join('operadora_grupo', 'operadora_grupo.id', 'operadora_grupo_medico.idoperadora_grupo_medico')
            ->where(['medico.ativo' => 'A', 'operadora_grupo.idoperadora' => $unidade->idoperadora])
            ->selectRaw("medico.id, concat(medico.crm, ' - ', pessoa_fisica.nome) nome")
            ->get();

        return view('vaga.cadastro', [
            'unidades' => $unidades,
            'especialidades' => $especialidades,
            'medicos' => $medicos,
            'modalidadesPag' => $modalidadesPag,
            'tabelaPrecos' => $tabelaPrecos,
            'tipoContratacoes' => $tipoContratacoes,
            'vaga' => $vaga,
            'unidadeSelecionada' => $unidade,
            'vagaTipoContratacao' => $tipoContratacao,
            'vagaModalidadePagamento' => $modalidadePagamento,
            'vagaRecorrencia' => $vagaRecorrencia
        ]);
    }

    public function store(Request $request)
    {

        $dados = $request->all();

        DB::beginTransaction();

        try {

            $dataInicio = Carbon::createFromFormat('d/m/Y H:i', $dados['data_inicio']);
        } catch (\Exception $e) {

            $dataInicio = '';
        }

        try {

            $dataFim = Carbon::createFromFormat('d/m/Y H:i', $dados['data_fim']);

        } catch (\Exception $e) {

            $dataFim = '';
        }

        try {

            $recorrencia = Carbon::createFromFormat('d/m/Y H:i', $dados['data_fimrecorrencia'])->format('Y-m-d H:i:s');

        } catch (\Exception $e) {

            $recorrencia = null;
        }

        $dataHoje = Carbon::now();

        $vaga = new Vaga();
        $vaga->idsala = $dados['sala'];
        $vaga->idespecialidade = $dados['especialidade'];
        $vaga->idtabela_valor = $dados['tabela_preco'];
        $vaga->data_inicio = $dataInicio->format('Y-m-d H:i:s');
        $vaga->data_final = $dataFim->format('Y-m-d H:i:s');
        $vaga->data_criacao = $dataHoje->format('Y-m-d');
        $vaga->bonus = str_replace(',', '.', $dados['bonus']);
        $vaga->observacao = $dados['observacao'];
        $vaga->visibilidade = $dados['visibilidade'];
        $vaga->recorrencia = $dados['recorrencia'];
        $vaga->possivel_clt = isset($dados['possivel_clt']) ? 1 : 0;
        $vaga->recorrencia_fim = $recorrencia;
        if(isset($dados['valor_hora'])){
            $vaga->valor_hora = str_replace(',', '.',$dados['valor_hora']);
        }
        if(isset($dados['valor_consulta'])){
            $vaga->valor_consulta = str_replace(',', '.',$dados['valor_consulta']);
        }
        $vaga->ativo = 'A';
        $vaga->idvaga_status = 1;
        $vaga->save();

        $vagaRecorrencia = new VagaRecorrencia();
        $vagaRecorrencia->idvaga = $vaga->id;
        $vagaRecorrencia->domingo = 0;
        $vagaRecorrencia->segunda = 0;
        $vagaRecorrencia->terca = 0;
        $vagaRecorrencia->quarta = 0;
        $vagaRecorrencia->quinta = 0;
        $vagaRecorrencia->sexta = 0;
        $vagaRecorrencia->sabado = 0;

        if(isset($dados['medico']) && $dados['medico'] != "")
        {

            $medicoVaga = new VagaCandidatura();
            $medicoVaga->idvaga = $vaga->id;
            $medicoVaga->idmedico = $dados['medico'];
            $medicoVaga->ativo = 'A';
            $medicoVaga->idvaga_status = VagaStatus::where(['nome' => 'Candidato escolhido'])->first()->id;
            $medicoVaga->tipo_contratacao_rpa = TipoContratacao::where(['nome' => 'CLT'])->first()->nome;
            $medicoVaga->save();

            $vaga = Vaga::find($vaga->id);
            $vaga->idvaga_status = VagaStatus::where(['nome' => 'Vaga preenchida'])->first()->id;
            $vaga->save();
        }

        if (isset($dados['recorrencias'])) {

            foreach ($dados['recorrencias'] as $recorrencias) {

                if ($recorrencias == 'dom') {

                    $vagaRecorrencia->domingo = 1;
                }

                if ($recorrencias == 'seg') {

                    $vagaRecorrencia->segunda = 1;
                }

                if ($recorrencias == 'ter') {

                    $vagaRecorrencia->terca = 1;
                }

                if ($recorrencias == 'qua') {

                    $vagaRecorrencia->quarta = 1;
                }

                if ($recorrencias == 'qui') {

                    $vagaRecorrencia->quinta = 1;
                }

                if ($recorrencias == 'sex') {

                    $vagaRecorrencia->sexta = 1;
                }

                if ($recorrencias == 'sab') {

                    $vagaRecorrencia->sabado = 1;
                }
            }
        }

        if(isset($dados['recorrencia']) && $dados['recorrencia'] != "")
        {

            $plantaoStatus = PlantaoStatus::where(['nome' => 'aberto'])->first();
            if(!$plantaoStatus){

                $plantaoStatus = new PlantaoStatus();
                $plantaoStatus->nome = 'aberto';
                $plantaoStatus->ativo = 'A';
                $plantaoStatus->save();
            }

            $recorrenciaInicioAtual = $dataInicio;
            $recorrenciaFinalAtual = $dataFim;

            for(;$recorrenciaInicioAtual->format('Y-m-d H:i:s') <= $recorrencia;){


                if($dados['recorrencia'] == 'Q'){

                    $plantao = new Plantao();
                    $plantao->idplantao_status = $plantaoStatus->id;
                    $plantao->idvaga = $vaga->id;
                    $plantao->data_inicio = $recorrenciaInicioAtual->format('Y-m-d H:i:s');
                    $plantao->data_termino = $recorrenciaFinalAtual->format('Y-m-d H:i:s');
                    $plantao->hora_planejada = $recorrenciaFinalAtual->diff($recorrenciaInicioAtual)->h;
                    $plantao->save();

                    $recorrenciaInicioAtual->addDays(15);
                    $recorrenciaFinalAtual->addDays(15);
                }elseif ($dados['recorrencia'] == 'M') {

                    $plantao = new Plantao();
                    $plantao->idplantao_status = $plantaoStatus->id;
                    $plantao->idvaga = $vaga->id;
                    $plantao->data_inicio = $recorrenciaInicioAtual->format('Y-m-d H:i:s');
                    $plantao->data_termino = $recorrenciaFinalAtual->format('Y-m-d H:i:s');
                    $plantao->hora_planejada = $recorrenciaFinalAtual->diff($recorrenciaInicioAtual)->h;
                    $plantao->save();

                    $recorrenciaInicioAtual->addMonth();
                    $recorrenciaFinalAtual->addMonth();
                }else{

                    if($vagaRecorrencia->domingo == 1 && $recorrenciaInicioAtual->isSunday()){

                        $plantao = new Plantao();
                        $plantao->idplantao_status = $plantaoStatus->id;
                        $plantao->idvaga = $vaga->id;
                        $plantao->data_inicio = $recorrenciaInicioAtual->format('Y-m-d H:i:s');
                        $plantao->data_termino = $recorrenciaFinalAtual->format('Y-m-d H:i:s');
                        $plantao->hora_planejada = $recorrenciaFinalAtual->diff($recorrenciaInicioAtual)->h;
                        $plantao->save();
                    }

                    if($vagaRecorrencia->segunda == 1 && $recorrenciaInicioAtual->isMonday()){

                        $plantao = new Plantao();
                        $plantao->idplantao_status = $plantaoStatus->id;
                        $plantao->idvaga = $vaga->id;
                        $plantao->data_inicio = $recorrenciaInicioAtual->format('Y-m-d H:i:s');
                        $plantao->data_termino = $recorrenciaFinalAtual->format('Y-m-d H:i:s');
                        $plantao->hora_planejada = $recorrenciaFinalAtual->diff($recorrenciaInicioAtual)->h;
                        $plantao->save();
                    }

                    if($vagaRecorrencia->terca == 1 && $recorrenciaInicioAtual->isTuesday()){

                        $plantao = new Plantao();
                        $plantao->idplantao_status = $plantaoStatus->id;
                        $plantao->idvaga = $vaga->id;
                        $plantao->data_inicio = $recorrenciaInicioAtual->format('Y-m-d H:i:s');
                        $plantao->data_termino = $recorrenciaFinalAtual->format('Y-m-d H:i:s');
                        $plantao->hora_planejada = $recorrenciaFinalAtual->diff($recorrenciaInicioAtual)->h;
                        $plantao->save();
                    }

                    if($vagaRecorrencia->quarta == 1 && $recorrenciaInicioAtual->isWednesday()){

                        $plantao = new Plantao();
                        $plantao->idplantao_status = $plantaoStatus->id;
                        $plantao->idvaga = $vaga->id;
                        $plantao->data_inicio = $recorrenciaInicioAtual->format('Y-m-d H:i:s');
                        $plantao->data_termino = $recorrenciaFinalAtual->format('Y-m-d H:i:s');
                        $plantao->hora_planejada = $recorrenciaFinalAtual->diff($recorrenciaInicioAtual)->h;
                        $plantao->save();
                    }

                    if($vagaRecorrencia->quinta == 1 && $recorrenciaInicioAtual->isThursday()){

                        $plantao = new Plantao();
                        $plantao->idplantao_status = $plantaoStatus->id;
                        $plantao->idvaga = $vaga->id;
                        $plantao->data_inicio = $recorrenciaInicioAtual->format('Y-m-d H:i:s');
                        $plantao->data_termino = $recorrenciaFinalAtual->format('Y-m-d H:i:s');
                        $plantao->hora_planejada = $recorrenciaFinalAtual->diff($recorrenciaInicioAtual)->h;
                        $plantao->save();
                    }

                    if($vagaRecorrencia->sexta == 1 && $recorrenciaInicioAtual->isFriday()){

                        $plantao = new Plantao();
                        $plantao->idplantao_status = $plantaoStatus->id;
                        $plantao->idvaga = $vaga->id;
                        $plantao->data_inicio = $recorrenciaInicioAtual->format('Y-m-d H:i:s');
                        $plantao->data_termino = $recorrenciaFinalAtual->format('Y-m-d H:i:s');
                        $plantao->hora_planejada = $recorrenciaFinalAtual->diff($recorrenciaInicioAtual)->h;
                        $plantao->save();
                    }

                    if($vagaRecorrencia->sabado == 1 && $recorrenciaInicioAtual->isSaturday()){

                        $plantao = new Plantao();
                        $plantao->idplantao_status = $plantaoStatus->id;
                        $plantao->idvaga = $vaga->id;
                        $plantao->data_inicio = $recorrenciaInicioAtual->format('Y-m-d H:i:s');
                        $plantao->data_termino = $recorrenciaFinalAtual->format('Y-m-d H:i:s');
                        $plantao->hora_planejada = $recorrenciaFinalAtual->diff($recorrenciaInicioAtual)->h;
                        $plantao->save();
                    }


                    $recorrenciaInicioAtual->addDays(1);
                    $recorrenciaFinalAtual->addDays(1);
                }
            }
        }



        $vagaRecorrencia->save();


        foreach ($dados['tipo_contratacao'] as $tipoContratacao) {

            $contratacao = new VagaTipoContratacao();
            $contratacao->idvaga = $vaga->id;
            $contratacao->idtipo_contratacao = $tipoContratacao;
            $contratacao->save();
        }

        foreach ($dados['modalidade_pagamento'] as $pagamento) {

            $modalidadePagamento = new VagaModalidadePagamento();
            $modalidadePagamento->idvaga = $vaga->id;
            $modalidadePagamento->idmodalidade_pagamento = $pagamento;
            $modalidadePagamento->save();
        }

        DB::commit();

        return redirect()->route('vaga.listar');
    }

    public function editarSalvar($idvaga, Request $request)
    {

        $dados = $request->all();

        if ($dados['submit'] == 'Excluir') {


            $vaga = Vaga::where(['id' => $idvaga])->first();
            $vaga->ativo = 'E';
            $vaga->save();
        } else {


            DB::beginTransaction();

            $dataHoje = Carbon::now();


            try {

                $dataInicio = Carbon::createFromFormat('d/m/Y H:i', $dados['data_inicio']);
            } catch (\Exception $e) {

                $dataInicio = '';
            }

            try {

                $dataFim = Carbon::createFromFormat('d/m/Y H:i', $dados['data_fim']);

            } catch (\Exception $e) {

                $dataFim = '';
            }

            try {

                $recorrencia = Carbon::createFromFormat('d/m/Y H:i', $dados['data_fimrecorrencia'])->format('Y-m-d H:i:s');

            } catch (\Exception $e) {

                $recorrencia = null;
            }

            $vaga = Vaga::where(['id' => $idvaga])->first();
            $vaga->idsala = $dados['sala'];
            $vaga->idespecialidade = $dados['especialidade'];
            $vaga->idtabela_valor = $dados['tabela_preco'];
            $vaga->data_inicio = $dataInicio->format('Y-m-d H:i:s');
            $vaga->data_final = $dataFim->format('Y-m-d H:i:s');
            $vaga->data_criacao = $dataHoje->format('Y-m-d');
            $vaga->bonus = str_replace(',', '.', $dados['bonus']);
            $vaga->observacao = $dados['observacao'];
            $vaga->visibilidade = $dados['visibilidade'];
            $vaga->recorrencia = $dados['recorrencia'];
            $vaga->possivel_clt = isset($dados['possivel_clt']) ? 1 : 0;
            $vaga->recorrencia_fim = $recorrencia;
            if(isset($dados['valor_hora'])){
                $vaga->valor_hora = str_replace(',', '.',$dados['valor_hora']);
            }
            if(isset($dados['valor_consulta'])){
                $vaga->valor_consulta = str_replace(',', '.',$dados['valor_consulta']);
            }
            $vaga->ativo = 'A';

            $vaga->idvaga_status = 1;
            $vaga->save();


            $vagaRecorrencia = VagaRecorrencia::where(['idvaga' => $idvaga])->first();

            $vagaRecorrencia->domingo = 0;
            $vagaRecorrencia->segunda = 0;
            $vagaRecorrencia->terca = 0;
            $vagaRecorrencia->quarta = 0;
            $vagaRecorrencia->quinta = 0;
            $vagaRecorrencia->sexta = 0;
            $vagaRecorrencia->sabado = 0;

            if (isset($dados['recorrencias'])) {

                foreach ($dados['recorrencias'] as $recorrencias) {

                    if ($recorrencias == 'dom') {

                        $vagaRecorrencia->domingo = 1;
                    }

                    if ($recorrencias == 'seg') {

                        $vagaRecorrencia->segunda = 1;
                    }

                    if ($recorrencias == 'ter') {

                        $vagaRecorrencia->terca = 1;
                    }

                    if ($recorrencias == 'qua') {

                        $vagaRecorrencia->quarta = 1;
                    }

                    if ($recorrencias == 'qui') {

                        $vagaRecorrencia->quinta = 1;
                    }

                    if ($recorrencias == 'sex') {

                        $vagaRecorrencia->sexta = 1;
                    }

                    if ($recorrencias == 'sab') {

                        $vagaRecorrencia->sabado = 1;
                    }
                }
            }

            $vagaRecorrencia->save();

            VagaTipoContratacao::where(['idvaga' => $idvaga])->delete();

            foreach ($dados['tipo_contratacao'] as $tipoContratacao) {

                $contratacao = new VagaTipoContratacao();
                $contratacao->idvaga = $idvaga;
                $contratacao->idtipo_contratacao = $tipoContratacao;
                $contratacao->save();
            }

            VagaModalidadePagamento::where(['idvaga' => $idvaga])->delete();

            foreach ($dados['modalidade_pagamento'] as $pagamento) {

                $modalidadePagamento = new VagaModalidadePagamento();
                $modalidadePagamento->idvaga = $idvaga;
                $modalidadePagamento->idmodalidade_pagamento = $pagamento;
                $modalidadePagamento->save();
            }

            DB::commit();
        }

        return redirect()->route('vaga.listar');
    }

    public function acompanhamento($idvaga)
    {

        $vaga = Vaga::find($idvaga);

        $vagasContratacao = VagaTipoContratacao::where(['idvaga' => $idvaga])->get();

        $vagasContratacao->map(function ($tipocontratacao) {

            $tipocontratacao->id = $tipocontratacao->tipoContratacao->id;
            $tipocontratacao->nome = $tipocontratacao->tipoContratacao->nome;
        });

        $medicosCandidatura = VagaCandidatura::where(['idvaga' => $idvaga])->get();

        $medicosCandidatura->map(function ($candidatura) use ($vaga){

            $medico = Medico::where(['id' => $candidatura->idmedico])->first();

            $candidatura->foto = $medico->foto;
            $candidatura->id = $candidatura->idmedico;
            $candidatura->nome = PessoaFisica::where(['idpessoa' => $medico->idpessoa])->first()->nome;
            $candidatura->crm = $medico->crm_uf . ' - ' . $medico->crm;
            $candidatura->data_candidatura = Carbon::createFromFormat('Y-m-d H:i:s', $candidatura->created_at)->format('d/m/Y');

            $candidatura->tipo_contratacao = $candidatura->tipo_contratacao_rpa;

           // $candidatura->especialidade_medico = $medico->especialidadeMedico->especialidade->nome;
            $candidatura->especialidade_medico = $vaga->especialidade->nome;

            $candidatura->status = VagaStatus::where([
                'id' => $candidatura->idvaga_status
            ])->first()->nome;
            /*
            if ($candidatura->ativo == 'A') {

                $candidatura->status = 'Candidato disponivel';
            } else {

                $candidatura->status = 'Candidato escolhido';
            }
            */

        });

        $medicos = Medico::join('pessoa_fisica', 'pessoa_fisica.idpessoa', 'medico.idpessoa')
            ->join('operadora_grupo_medico', 'operadora_grupo_medico.idmedico', 'medico.id')
            ->join('operadora_grupo', 'operadora_grupo.id', 'operadora_grupo_medico.idoperadora_grupo_medico')
            ->join('medico_especialidade', 'medico_especialidade.idmedico', 'medico.id')
            ->where([
                'medico.ativo' => 'A',
                'operadora_grupo.idoperadora' => $vaga->sala->unidade->idoperadora,
                'medico_especialidade.idespecialidade' => $vaga->idespecialidade])
            ->selectRaw("medico.id, concat(medico.crm, ' - ', pessoa_fisica.nome) nome")
            ->get();

        $tabelaValor = TabelaValor::where(['id' => $vaga->idtabela_valor])->first();


        //if ($tabelaValor) {

          //  $valores = Valor::where([
            //    'idtabela_valor' => $tabelaValor->id,
              //  'idespecialidade' => $vaga->idespecialidade
            //])->get();
        //} else {

            $valores = [];
        //}

        $vagaContratacao = new VagaTipoContratacao();
        $vagaContratacao->idvaga = $idvaga;

        $plantoes = Plantao::select(
                DB::raw('sum(plantao_atendimento.quantidadeAtendimento) as atendimentos'),
                'plantao.id',
                'pessoa_fisica.nome as medico',
                'plantao.idvaga',
                'plantao.idmedico',
                'plantao.data_inicio',
                'plantao.data_termino',
                'plantao.check_in',
                'plantao.check_out',
                'plantao.idplantao_status',
                'plantao.idtipo_contratacao',
                'plantao.hora_planejada',
            )
            ->leftJoin('plantao_atendimento', 'plantao.id', 'plantao_atendimento.idplantao')
            ->join('medico', 'plantao.idmedico', 'medico.id')
            ->join('pessoa', 'medico.idpessoa', 'pessoa.id')
            ->join('pessoa_fisica', 'pessoa.id', 'pessoa_fisica.idpessoa')
            ->where([
                'idvaga' => $idvaga
            ])
            ->groupBy('plantao.id', 'pessoa_fisica.nome', 'plantao.idvaga', 'plantao.idmedico', 'plantao.data_inicio', 'plantao.data_termino', 'plantao.check_in', 'plantao.check_out', 'plantao.idplantao_status', 'plantao.idtipo_contratacao', 'plantao.hora_planejada')
            ->paginate(5);

        return view('vaga.acompanhamento', [
            'vaga' => $vaga,
            'medicos' => $medicos,
            'medicosCandidatura' => $medicosCandidatura,
            'idvaga' => $idvaga,
            'vagaContratacao' => $vagaContratacao,
            'vagasContratacao' => $vagasContratacao,
            'valores' => $valores,
            'plantoes' => $plantoes
        ]);
    }

    public function retornarDadosMedico($idmedico, $idvaga, $idtipocontratacao)
    {

        $medico = Medico::find($idmedico);

        $vaga = Vaga::find($idvaga);

        $medico->nome = PessoaFisica::where(['idpessoa' => $medico->idpessoa])->first()->nome;

        if (!VagaCandidatura::where(['idvaga' => $idvaga, 'idmedico' => $idmedico])->first()) {
            $medicoVaga = new VagaCandidatura();
            $medicoVaga->idvaga = $idvaga;
            $medicoVaga->idmedico = $idmedico;
            $medicoVaga->ativo = 'A';
            $medicoVaga->idvaga_status = VagaStatus::where(['nome' => 'Candidato disponível'])->first()->id;
            $medicoVaga->tipo_contratacao_rpa = TipoContratacao::find($idtipocontratacao)->nome;
            $medicoVaga->save();

            $medico->data_candidatura = Carbon::createFromFormat('Y-m-d H:i:s', $medicoVaga->created_at)->format('d/m/Y');
            $medico->status = 'Candidato disponível';
            $medico->tipo_contratacao_rpa = $medicoVaga->tipo_contratacao_rpa;
            $medico->especialidade_medico = $vaga->especialidade->nome;

            return response()->json($medico);
        }
    }


    public function removerMedicoVaga($idmedico, $idvaga)
    {

        VagaCandidatura::where(['idvaga' => $idvaga, 'idmedico' => $idmedico])->delete();
    }

    public function aprovarMedicoVaga($idmedico, $idvaga)
    {

        DB::beginTransaction();

        $candidatura = VagaCandidatura::where(['idvaga' => $idvaga, 'idmedico' => $idmedico])->first();
        $candidatura->idvaga_status = VagaStatus::where(['nome' => 'Candidato escolhido'])->first()->id;
        $candidatura->save();

        $vaga = Vaga::find($idvaga);
        $vaga->idvaga_status = VagaStatus::where(['nome' => 'Vaga preenchida'])->first()->id;
        $vaga->save();

        $plantoes = Plantao::whereNull('idmedico')->where(['idvaga' => $idvaga])->get();

        if($plantoes){

            foreach ($plantoes as $plantao){

                $plantao->idmedico = $idmedico;
                $plantao->save();
            }
        }


        DB::commit();
    }

    public function trocarMedicoVaga(Request $request, $idvaga)
    {
        $data = $request->data_troca;
        
        if($data != ""){            
            $dataDaTroca = Carbon::createFromFormat('d/m/Y H:i', $data)->format('Y-m-d H:i');
        }

        $medicoTitular = PessoaFisica::select('medico.id')
            ->join('medico', 'pessoa_fisica.idpessoa', 'medico.idpessoa')
            ->where('pessoa_fisica.nome', $request->medico_titular)
            ->first();

        if ($medicoTitular->id != "") {
            $medico = VagaCandidatura::where(['idvaga' => $idvaga, 'idmedico' => $medicoTitular->id])->first();
            $medico->idvaga_status = VagaStatus::where(['nome' => 'Trocar a vaga'])->first()->id;
            $medico->save();
        }

        $candidatura = VagaCandidatura::where(['idvaga' => $idvaga, 'idmedico' => $request->medico])->first();
        $candidatura->idvaga_status = VagaStatus::where(['nome' => 'Candidato escolhido'])->first()->id;
        $candidatura->save();
        
        $vaga = Vaga::find($idvaga);
        $vaga->idvaga_status = VagaStatus::where(['nome' => 'Trocar o plantão'])->first()->id;
        $vaga->save();
        
        $plantoes = Plantao::where(['idvaga' => $idvaga])->get();

        if($plantoes){            
            foreach ($plantoes as $plantao) {
                if ($dataDaTroca <= $plantao->data_inicio) {
                    $plantao->update(['idmedico' => $request->medico]);
                }
            }
        }

        return redirect()->route('vaga.acompanhamento', $idvaga);
    }

    public function trocarMedicoPlantao(Request $request, $idvaga)
    {
        $plantao = Plantao::where([
            'id' => $request->plantao, 
            'idvaga' => $idvaga
        ])->first();

        $plantao->idmedico = $request->medico;
        $plantao->idplantao_status = PlantaoStatus::where(['nome' => 'Aguardando troca'])->first()->id;
        $plantao->save();

        return redirect()->route('vaga.acompanhamento', $idvaga);
    }

    public function cancelarVaga($id)
    {

        $vaga = Vaga::find($id);
        $vaga->ativo = 'I';
        $vaga->save();
    }

    private function remover($id)
    {

        $operadora = Vaga::find($id);
        $operadora->ativo = 'E';
        $operadora->save();
    }

    private function inativar($id)
    {

        $operadora = Vaga::find($id);
        $operadora->ativo = 'I';
        $operadora->save();
    }

    private function ativar($id)
    {

        $operadora = Vaga::find($id);
        $operadora->ativo = 'A';
        $operadora->save();
    }
}