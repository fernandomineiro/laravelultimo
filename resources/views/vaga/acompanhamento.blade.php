@extends('layouts.app')

@section('content')

    <div id="acompanhamentos">
        <div class="card">
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{ route('vaga.listar') }}">Vaga</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><strong>Acompanhamento</strong></li>
                    </ol>
                </nav>
                <div class="row">
                    <div class="col-md-3">
                        <p><b>Escala:</b> #000{{$vaga->id}}</p>
                        <p><b>Data:</b> {{$vaga->dataInicioFormatada()}} - {{$vaga->dataRecorrenciaFim()}}</p>
                        <p><b>Modalidade de Pagamento:</b><br> {!! $vaga->pagamentoTexto() !!}</p>
                        <p><b>Observação:</b></p>
                        @if(strlen($vaga->observacao) < 70)
                            <span>{{$vaga->observacao}}</span>
                        @else
                            <span>{{substr($vaga->observacao, 0, 70)}} <a href="#" data-toggle="tooltip" title="{{$vaga->observacao}}" data-original-title="{{$vaga->observacao}}">...</a></span>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <p><b>Status:</b> @{{status}}</p>
                        <p><b>Horário:</b> {{$vaga->dataInicioSoHoraFormatada()}} - {{$vaga->dataFinalSoHoraFormatada()}}</p>
                        <p><b>Tipo Contratação: </b><br>
                            {!! $vagaContratacao->tiposTexto() !!}
                        </p>

                    </div>
                    <div class="col-md-3">
                        <p><b>Unidade:</b> {{$vaga->sala->unidade->nome}} - {{$vaga->sala->nome}}</p>

                        <p><b>Recorrencia:</b>
                        {{$vaga->recorrenciaResultado()}}
                        @if($vaga->diasTexto() != '')
                            <p><b>Dias:</b> {{$vaga->diasTexto()}}</p>
                        @else
                            <span><b>Até:</b> {{$vaga->dataRecorrenciaFim()}}</span>
                        @endif
                        </p>
                        <p><b>Visibilidade:</b> {{$vaga->visibilidadeResultado()}}</p>
                    </div>
                    <div class="col-md-3">
                        @if($vaga->status() != 'Candidato confirmado')
                            <div class="float-right">
                                <button type="button" class="btn btn-sm btn-gray" data-toggle="tooltip" data-placement="bottom" title="Cancelar Vaga">
                                    <i class="fa fa-ban"></i>
                                </button>
                                <a href="{{route('vaga.acompanhamento.editar', ['idvaga' => $idvaga])}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Editar Vaga">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </div>
                        @endif
                        <p><b>Especialidade:</b> {{$vaga->especialidade->nome}}</p>
                        <p><b>Tabela de Valor:</b></p>
                        @if($valores)
                            @foreach($valores as $valor)
                                <span>  {{isset($valor->valor_rpa) ? 'RPA: R$ ' . $valor->valor_rpa : ''}} {{isset($valor->valor_clt) ? 'CLT: R$ ' . $valor->valor_clt : ''}}</span>
                            @endforeach
                        @elseif(isset($vaga->valor_hora) && !empty($vaga->valor_hora))
                                <span>Hora: R$ {{$vaga->valor_hora}} Consulta: R$ {{$vaga->valor_consulta}}</span>
                        @endif
                    </div>
                </div>
                @if($vaga->status() != 'Candidato confirmado')
                <div class="row">
                    <div class="col-md-7">
                        <p>Médico:</p>
                        <select id="medico" class="select2" name="pesquisa"
                                placeholder="Digite o nome do médico para adicionar" style="width: 100%"
                                v-model="medico">
                            <option v-for="medico in medicos" v-bind:value="medico.id">@{{ medico.nome }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <p>Tipo Contratação:</p>
                        <select id="tipo_contratacao" class="select2" name="tipo_contratacao"
                                placeholder="Digite o tipo de contratacao" style="width: 100%"
                                v-model="tipoContratacao">
                            <option v-for="vagatipocontratacao in vagasContratacao" v-bind:value="vagatipocontratacao.id">@{{ vagatipocontratacao.nome }}</option>

                            <option></option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-sm btn-gray btn-add-acompanhamento" data-toggle="tooltip" data-placement="bottom" title="Adicionar" v-on:click="adicionar">
                            <i class="fa fa-plus-circle"></i>
                        </button>
                    </div>
                </div>
                @else
                    <div class="row">
                        <div class="col-md-7">
                            <p>Médico:</p>
                            <b>{{$vaga->medicoEscolhido()}}</b>
                            <button type="button" id="trocar_medico" class="btn btn-sm btn-gray" data-toggle="modal" data-placement="bottom" title="Trocar Médico Titular" data-target="#modalTroca">
                                <i class="fa fa-edit"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <section id="medicos">
            <div class="card">
                <div class="card-body">
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Crm</th>
                            <th>Tipo Contratação</th>
                            <th>Dt Candidatura</th>
                            <th>Status</th>
                            <th>Plantões</th>
                            <th>Atrasos</th>
                            <th>Especializações</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tr v-for="(medicoList, index) in medicosList">
                            <td>
                                <img :src="medicoList.foto" width="60px">
                            </td>
                            <td width="20%" class="bd-callout-primary callout-border-left callout-transparent">
                                @{{ medicoList.nome }}
                            </td>
                            <td>
                                @{{ medicoList.crm }}
                            </td>
                            <td>
                                @{{ medicoList.tipo_contratacao }}
                            </td>
                            <td>
                                @{{ medicoList.data_candidatura }}
                            </td>
                            <td>
                                @{{ medicoList.status }}
                            </td>
                            <td>
                                @{{ medicoList.plantoes }}
                            </td>
                            <td>
                                @{{ medicoList.atrasos }}
                            </td>
                            <td>
                                @{{ medicoList.especialidade_medico }}
                            </td>
                            <td width="8%">
                                <button class="btn btn-sm btn-gray" v-on:click="aprovar(medicoList.id, medicoList.nome)" data-toggle="tooltip" data-placement="bottom" title="Aprovar">
                                    <i class="fa fa-plus-circle"></i>
                                </button>
                                <button class="btn btn-sm btn-dark" v-on:click="remover(index, medicoList.id)" data-toggle="tooltip" data-placement="bottom" title="Remover">
                                    <i class="fa fa-minus-circle"></i>
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>
        @if($vaga->status() == 'Candidato confirmado')
        <section id="plantoes">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Médico</th>
                                <th>Data inicio</th>
                                <th>Data termino</th>
                                <th>Dia Semana</th>
                                <th>Tipo Contratação</th>
                                <th>Status Plantão</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Hrs. Planejadas</th>
                                <th>Hrs. Realizadas</th>
                                <th>Atend.</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($plantoes))
                                @foreach($plantoes as $plantao)
                                    <tr>
                                        <td>{{$plantao->medico}}</td>
                                        <td>{{$plantao->dataHoraInicioFormatada()}}</td>
                                        <td>{{$plantao->dataHoraTerminoFormatada()}}</td>
                                        <td>{{$plantao->diaSemanaFormatada()}}</td>
                                        <td>{{$plantao->tipoContratacao()->first()->nome}}</td>
                                        <td>{{$plantao->plantaoStatus()->first()->nome}}</td>
                                        <td>{{$plantao->checkIn()}}</td>
                                        <td>{{$plantao->checkOut()}}</td>
                                        <td>{{$plantao->hora_planejada}}</td>
                                        <td>{{$plantao->horasRealizadas()}}</td>
                                        <td>{{$plantao->atendimentos}}</td>
                                        <td>
                                            @if ($plantao->checkIn() == null && $plantao->data_termino < date('Y-m-d H:i') && $plantao->plantaoStatus()->first()->nome == 'aberto')
                                                <button type="button" class="btn btn-sm btn-gray trocar_plantao"
                                                data-toggle="modal" data-placement="bottom" data-target="#modalTrocaPlantao" title="Trocar plantão" value="{{$plantao->id}}" disabled>
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @elseif($plantao->checkIn() == null)
                                                <button type="button" class="btn btn-sm btn-gray trocar_plantao"
                                                data-toggle="modal" data-placement="bottom" data-target="#modalTrocaPlantao" title="Trocar plantão" value="{{$plantao->id}}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                <div class="col-md-3" style="margin: 0 auto;">{{$plantoes->links()}}</div>
                </div>
            </div>
        </section>
        @endif
        <div class="area-modal">
            <div class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmar</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Confirmar @{{ medicoModal.nome }} para o plantão?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" v-on:click="salvarModal">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalTroca" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Trocar Médico</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('vaga.acompanhamento.trocar-medico-vaga', $vaga->id) }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="data_troca">A partir de qual data será realizada a troca?</label>
                                        <input type="text" class="form-control" id="data_troca" data-toggle="datetimepicker" data-target="#data_troca" name="data_troca">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="medico">Médico</label>
                                    <div class="form-group">
                                        <select name="medico" id="medico" class="form-control">
                                            <option v-for="(medicoList, index) in medicosList"  v-bind:value="medicoList.id">@{{ medicoList.nome }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="medico_titular" value="{{$vaga->medicoEscolhido()}}">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Trocar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalTrocaPlantao" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Trocar Plantão</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="troca" method="POST" action="{{route('vaga.acompanhamento.trocar-medico-plantao', $vaga->id)}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="medico">Médico</label>
                                    <div class="form-group">
                                        <select name="medico" id="medico" class="form-control">
                                            <option v-for="medico in medicos"  v-bind:value="medico.id">@{{ medico.nome }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="medico_titular" value="{{$vaga->medicoEscolhido()}}">
                            <input id="plantao_id" type="hidden" name="plantao" value="">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Trocar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>

        $(document).ready(function () {
            $('.select2').select2();

            $('#data_troca').datetimepicker({
                format: 'DD/MM/Y HH:mm',
                locale: 'pt-BR'
            });

            $('.trocar_plantao').on('click', function() {
                var btnValue = $(this).val();
                $('#plantao_id').val(btnValue);
            })
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        var app = new Vue({
            el: '#acompanhamentos',
            data: {
                status: '<?php echo $vaga->status() ?>',
                medico: undefined,
                medicos: <?php echo json_encode($medicos)?>,
                medicosList: <?php echo json_encode($medicosCandidatura) == '{}' ? '[]' : json_encode($medicosCandidatura)?>,
                vagasContratacao: <?php echo json_encode($vagasContratacao)?>,
                tipoContratacao: '',
                medicoModal: {'id': '', 'nome': ''}
            },
            methods: {
                adicionar: function (event) {

                	this.$http.get('{{route("vaga.acompanhamento.retornar-dados-medico", ["idmedico" => "", 'idvaga' => '', 'idtipocontratacao' => ''])}}/' + $('#medico').val() + '/' + '{{$vaga->id}}/' + $('#tipo_contratacao').val()).then(response => {

                        // get body data
                        var medico = response.body;

                        console.log(medico);
                        if(medico != '' && medico != undefined)
                            this.medicosList.push({
                                'foto': medico.foto,
                                'nome': medico.nome,
                                'crm': medico.crm_uf + ' - '+ medico.crm,
                                'id': medico.id,
                                'data_candidatura': medico.data_candidatura,
                                'status': medico.status,
                                'tipo_contratacao': medico.tipo_contratacao_rpa,
                                'especialidade_medico': medico.especialidade_medico
                            });

                        this.status = 'Com candidados';
                   });
                    
                    console.log(this.medicosList);
                },
                aprovar: function(medicoId, medicoNome){

                    this.status = 'Vaga preenchida';
                    this.medicoModal.id = medicoId;
                    this.medicoModal.nome = medicoNome;
                    $('.modal').modal('show');
                },
                salvarModal: function(){

                    this.$http.get('{{route("vaga.acompanhamento.aprovar-medico-vaga", ["idmedico" => "", "id" => ""])}}/' + this.medicoModal.id + '/' + '{{$vaga->id}}').then(response => {

                        window.location.reload();
                    });
                },
                remover:function (index, idMedico) {

                    this.$http.get('{{route("vaga.acompanhamento.remover-medico-vaga", ["idmedico" => "", "id" => ""])}}/' + idMedico + '/' + '{{$vaga->id}}').then(response => {

                        this.medicosList.splice(index, 1);

                        this.status = 'Sem candidatos';
                    });

                },
                cancelar:function(){


                    this.$http.get('{{route("vaga.acompanhamento.cancelar-vaga", ["id" => $vaga->id])}}').then(response => {

                       window.location.href = '{{route("vaga.listar")}}';
                    });
                }
            }
        });
    </script>
@endsection