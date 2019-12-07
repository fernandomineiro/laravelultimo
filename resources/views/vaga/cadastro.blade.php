@extends('layouts.app')

@section('content')

    @php

        use Carbon\Carbon;

        if(isset($vaga)){

            $dataInicio = Carbon::createFromFormat('Y-m-d H:i:s', $vaga->data_inicio)->format('d/m/Y H:i');
            $dataFim = Carbon::createFromFormat('Y-m-d H:i:s', $vaga->data_final)->format('d/m/Y H:i');

            if($vaga->recorrencia_fim != ''){

                $dataRecorrencia = Carbon::createFromFormat('Y-m-d H:i:s', $vaga->recorrencia_fim)->format('d/m/Y H:i');
            }
        }else{

            $dataInicio = '';
            $dataFim = '';
        }

    @endphp
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
     <div class="card" id="vagas">
        <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('vaga.listar') }}">Vaga</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><strong>Edição</strong></li>
                </ol>
            </nav>
            <div id="vaga">
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="alert alert-danger d-none" id="errors-data">
                        <ul>
                            <li class="d-none" id="errors-data-li">Data inicio é maior que a data final</li>
                            <li class="d-none" id="errors-tabela-li">Tabela de valor é obrigatório</li>
                            <li class="d-none" id="errors-sala-li">Sala é obrigatório</li>
                            <li class="d-none" id="errors-unidade-li">Unidade é obrigatório</li>
                            <li class="d-none" id="errors-especialidade-li">Especialidade é obrigatório</li>
                            <li class="d-none" id="errors-data_fimrecorrencia-li">Data fim da recorrencia é obrigatório</li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="escala">Escala</label>
                                <input type="text" class="form-control" id="escala" name="escala" value="#000{{isset($vaga->id) ? $vaga->id : ''}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unidade">Unidade</label>
                                <select class="form-control" name="unidade" id="unidade" v-model="unidade">
                                    <option value="0"></option>
                                    @foreach($unidades as $unidade)
                                        <option value="{{$unidade->id}}">{{$unidade->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unidade">Sala</label>
                                <select class="form-control" name="sala" id="sala" v-model="sala" v-bind:readonly="!isDisabledSala">
                                    <option v-for="s in salas" :value="s.id">@{{s.nome}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="especialidade">Especialidade</label>
                                <select class="form-control" name="especialidade" id="especialidade" v-model="especialidade" v-bind:readonly="!isDisabledSala">
                                    <option v-for="e in especialidades" :value="e.id">@{{e.nome}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="data_inicio">Início</label>
                                <input type="text" class="form-control" id="data_inicio" name="data_inicio" data-toggle="datetimepicker" data-target="#data_inicio" value="{{$dataInicio}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="data_fim">Fim</label>
                                <input type="text" class="form-control" id="data_fim" name="data_fim" data-toggle="datetimepicker" data-target="#data_fim" value="{{$dataFim}}">
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="medico">Médico</label>
                                <select class="form-control" name="medico" id="medico">

                                </select>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tabela_preco">Tabela de valor</label>
                                <select class="form-control" name="tabela_preco" id="tabela_preco" v-model="tabela_preco" v-bind:readonly="!isDisabledSala">
                                    <option value="">Selecione</option>
                                    <option v-for="p in precos" :value="p.id">@{{p.nome}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bonus">Bônus</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">R$</div>
                                    </div>
                                    <input type="text" class="form-control" id="bonus" name="bonus" value="{{isset($vaga->bonus) ? str_replace('.',',',$vaga->bonus) : ''}}" maxlength="10" data-affixes-stay="true" data-prefix=" " data-thousands="." data-decimal=",">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="tabela_preco == ''">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="valor_hora">Valor hora</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">R$</div>
                                    </div>
                                    <input type="text" class="form-control" name="valor_hora" id="valor_hora" value="{{isset($vaga->valor_hora) ? str_replace('.',',',$vaga->valor_hora) : ''}}" maxlength="10" data-affixes-stay="true" data-prefix=" " data-thousands="." data-decimal=",">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="valor_consulta">Valor consulta</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">R$</div>
                                    </div>
                                    <input type="text" class="form-control" name="valor_consulta" id="valor_consulta" value="{{isset($vaga->valor_consulta) ? str_replace('.',',',$vaga->valor_consulta) : ''}}" maxlength="10" data-affixes-stay="true" data-prefix=" " data-thousands="." data-decimal=",">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modalidade_pagamento">Modalidade de pagamento</label>
                                @foreach($modalidadesPag as $modalidadePag)
                                    <label class="" for="modalidade-{{$modalidadePag->id}}">
                                        <input type="checkbox" class="" id="modalidade-{{$modalidadePag->id}}" name="modalidade_pagamento[]" value="{{$modalidadePag->id}}" {{isset($vagaModalidadePagamento) && $vagaModalidadePagamento->verificarModalidade($modalidadePag->id) ? 'checked' : ''}}>
                                        {{$modalidadePag->nome}}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clt">Tipo de contratação</label>
                                @foreach($tipoContratacoes as $tipoContratacao)
                                    <label class="" for="contrato-{{$tipoContratacao->id}}">
                                        <input type="checkbox" class="" id="contrato-{{$tipoContratacao->id}}" name="tipo_contratacao[]" value="{{$tipoContratacao->id}}" {{isset($vagaTipoContratacao) && $vagaTipoContratacao->verificarTipoContratacao($tipoContratacao->id) ? 'checked' : ''}}>
                                        {{$tipoContratacao->nome}}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="possivel_clt">Possível CLT</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="possivel_clt" name="possivel_clt" {{isset($vaga->possivel_clt) && $vaga->possivel_clt == '1' ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="possivel_clt"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="visibilidade">Visibilidade</label>
                                <select class="form-control" name="visibilidade" id="visibilidade">
                                    <option value="P" {{isset($vaga->visibilidade) && $vaga->visibilidade == 'P' ? 'selected' : ''}}>Público</option>
                                    <option value="O" {{isset($vaga->visibilidade) && $vaga->visibilidade == 'O' ? 'selected' : ''}}>Profissionais da Operadora</option>
                                    <option value="G" {{isset($vaga->visibilidade) && $vaga->visibilidade == 'G' ? 'selected' : ''}}>Participantes de Grupos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="recorrencia">Médico</label>
                                <select class="form-control" name="medico" id="medico" v-model="medico">
                                        <option value="">Selecione</option>
                                    @foreach($medicos as $medico)
                                        <option value="{{$medico->id}}">{{$medico->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="recorrencia">Recorrencia</label>
                                <select class="form-control" name="recorrencia" id="recorrencia" v-model="recorrencia">
                                    <option value="Q">Quinzenal</option>
                                    <option value="S">Semanal</option>
                                    <option value="M">Mensal</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="data_fim">Recorrencia Fim</label>
                                <input type="text" class="form-control" id="data_fimrecorrencia" name="data_fimrecorrencia" data-format="DD/MM/Y" data-toggle="datetimepicker" data-target="#data_fimrecorrencia" value="{{isset($vaga->recorrencia_fim) ? $dataRecorrencia : ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="recorrencia == 'S'">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="modalidade_pagamento">Dias semanas recorrencia</label>
                                <br/>
                                <label for="recorrencia-dom">
                                    <input type="checkbox" id="recorrencia-dom" name="recorrencias[]" value="dom" {{isset($vagaRecorrencia->domingo) && $vagaRecorrencia->domingo == 1 ? 'checked' : ''}}>
                                    Dom
                                </label>
                                <label for="recorrencia-seg">
                                    <input type="checkbox" id="recorrencia-seg" name="recorrencias[]" value="seg" {{isset($vagaRecorrencia->segunda) && $vagaRecorrencia->segunda == 1 ? 'checked' : ''}}>
                                    Seg
                                </label>
                                <label for="recorrencia-ter">
                                    <input type="checkbox" id="recorrencia-ter" name="recorrencias[]" value="ter" {{isset($vagaRecorrencia->terca) && $vagaRecorrencia->terca == 1 ? 'checked' : ''}}>
                                    Ter
                                </label>
                                <label for="recorrencia-qua">
                                    <input type="checkbox" id="recorrencia-qua" name="recorrencias[]" value="qua" {{isset($vagaRecorrencia->quarta) && $vagaRecorrencia->quarta == 1 ? 'checked' : ''}}>
                                    Qua
                                </label>
                                <label for="recorrencia-qui">
                                    <input type="checkbox" id="recorrencia-qui" name="recorrencias[]" value="qui" {{isset($vagaRecorrencia->quinta) && $vagaRecorrencia->quinta == 1 ? 'checked' : ''}}>
                                    Qui
                                </label>
                                <label for="recorrencia-sex">
                                    <input type="checkbox" id="recorrencia-sex" name="recorrencias[]" value="sex" {{isset($vagaRecorrencia->sexta) && $vagaRecorrencia->sexta == 1 ? 'checked' : ''}}>
                                    Sex
                                </label>
                                <label for="recorrencia-sab">
                                    <input type="checkbox" id="recorrencia-sab" name="recorrencias[]" value="sab" {{isset($vagaRecorrencia->sabado) && $vagaRecorrencia->sabado == 1 ? 'checked' : ''}}>
                                    Sáb
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <textarea  class="form-control" id="observacao" name="observacao">{{isset($vaga->observacao) ? $vaga->observacao : ''}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end">
                                <a href="{{route('vaga.listar')}}" class="btn btn-secondary" data-dismiss="modal">Cancelar</a>
                                @if(isset($vaga))
                                    <input id="excluir" name="submit" type="submit" class="btn btn-gray" style="margin-left:10px;" value="Excluir">
                                @endif
                                <input id="salvar" name="submit" type="submit" class="btn btn-primary" style="margin-left:10px;" value="Salvar">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>

    $(document).ready(function () {

        $('#bonus').maskMoney();

        $('#valor_consulta').maskMoney();

        $('#valor_hora').maskMoney();

        $('#data_inicio').datetimepicker({
            format: 'DD/MM/Y HH:mm'
        });

        $('#data_fim').datetimepicker({
            format: 'DD/MM/Y HH:mm'
        });

        $('#data_fimrecorrencia').datetimepicker({
            format: 'DD/MM/Y HH:mm'
        });

        $('#salvar').on('click',function (event) {

            var momentA = moment($('#data_inicio').val(),"DD/MM/YYYY");
            var momentB = moment($('#data_fim').val(),"DD/MM/YYYY");

            if (momentA > momentB){

                event.preventDefault();

                $("#errors-data").removeClass('d-none');

                $("#errors-data-li").removeClass('d-none');

                window.scrollTo(0, 0);
            }

            // if($('#tabela_preco').val() == '' || $('#sala').val() == null){
            //
            //     event.preventDefault();
            //
            //     $("#errors-data").removeClass('d-none');
            //
            //     $("#errors-tabela-li").removeClass('d-none');
            //
            //     window.scrollTo(0, 0);
            // }

            if($('#sala').val() == '' || $('#sala').val() == null){

                event.preventDefault();

                $("#errors-data").removeClass('d-none');

                $("#errors-sala-li").removeClass('d-none');

                window.scrollTo(0, 0);
            }

            if($('#unidade').val() == '' || $('#unidade').val() == null || $('#unidade').val() == '0'){

                event.preventDefault();

                $("#errors-data").removeClass('d-none');

                $("#errors-unidade-li").removeClass('d-none');

                window.scrollTo(0, 0);
            }

            if($('#especialidade').val() == '' || $('#especialidade').val() == null){

                event.preventDefault();

                $("#errors-data").removeClass('d-none');

                $("#errors-especialidade-li").removeClass('d-none');

                window.scrollTo(0, 0);
            }

            if($('#data_fimrecorrencia').val() == '' || $('#data_fimrecorrencia').val() == null){

                event.preventDefault();

                $("#errors-data").removeClass('d-none');

                $("#errors-data_fimrecorrencia-li").removeClass('d-none');

                window.scrollTo(0, 0);
            }


        });

        @if(isset($vaga->recorrencia_fim))
        setTimeout(function () {

            $('#data_fimrecorrencia').val('{{isset($vaga->recorrencia_fim) ? $dataRecorrencia : ''}}');
        }, 300)
        @endif
    });
    var app = new Vue({
        el: '#vagas',
        data:{
            isDisabledSala: "{{isset($vaga->idsala) ? true : false}}",
            salas: [],
            especialidades: [],
            precos: [],
            recorrencia: "{{isset($vaga->recorrencia) ? $vaga->recorrencia : ''}}",
            especialidade: "{{isset($vaga->idespecialidade) ? $vaga->idespecialidade : ''}}",
            unidade: "{{isset($unidadeSelecionada->id) ? $unidadeSelecionada->id : '0'}}",
        	sala: "{{isset($vaga->idsala) ? $vaga->idsala : ''}}",
            tabela_preco: "{{isset($vaga->idtabela_valor) ? $vaga->idtabela_valor : ''}}",
            medico: ""
        },
        methods:{

        },
        mounted:function(){

            if(this.unidade != ""){

                this.$http.get('{{route("sala.buscar.buscar-por-unidade")}}/' + this.unidade).then(response => {

                    this.salas = response.body.salas;

                    this.isDisabledSala = true;
                });

                this.$http.get('{{route("tabela.buscar.buscar-por-unidade")}}/' + this.unidade).then(response => {

                    this.precos = response.body.vagas;

                    this.isDisabledSala = true;
                });

                if(this.sala != ""){

                    this.$http.get('{{route("especialidade.buscar.buscar-por-sala", ['id' => ''])}}/' + this.sala).then(response => {

                        this.especialidades = response.body.especialidades;

                        this.isDisabledSala = true;
                    });
                }
            }
        },
        watch: {
            tabela_preco: function (val){

                setTimeout(function(){

                    $('#valor_consulta').maskMoney();

                    $('#valor_hora').maskMoney();
                }, 500);
            },
            recorrencia: function (val){

                setTimeout(function () {

                    $('#data_fimrecorrencia').datetimepicker({
                        format: 'DD/MM/Y'
                    });
                }, 200);

            },
            unidade: function (val) {

                if(val != ""){

                    this.$http.get('{{route("tabela.buscar.buscar-por-unidade")}}/' + val).then(response => {

                        this.precos = response.body.vagas;

                        this.isDisabledSala = true;
                    });
                }else{

                    this.isDisabledSala = false;
                }

                if(val != ""){

                    this.$http.get('{{route("sala.buscar.buscar-por-unidade")}}/' + val).then(response => {

                        this.salas = response.body.salas;

                        this.isDisabledSala = true;
                    });
                }else{

                    this.isDisabledSala = false;
                }
            },

            sala: function (val) {

                if(val != ""){

                    this.$http.get('{{route("especialidade.buscar.buscar-por-sala")}}/' + this.sala).then(response => {

                        this.especialidades = response.body.especialidades;

                        this.isDisabledSala = true;
                    });
                }else{

                    this.isDisabledSala = false;
                }

            }
        }
    });
</script>
@endsection