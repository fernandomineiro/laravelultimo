@extends('layouts.app')

@section('content')
    
<div class="card" id="app">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><strong>Plantão</strong></li>
            </ol>
        </nav>
        <div id="registros">             
            <form id="busca" method="post" action="{{route('plantao.listar')}}">
                @csrf
                <div id="form-acoes" class="form-group">
                    {{-- Campo de pesquisa --}}
                    <div class="input-group">
                        <input type="text" name="filtro" id="filtro" class="form-control form-control-md" placeholder="Filtro">
                        <div class="input-group-append">

                            {{-- btn-consultar --}}
                            <button type="button" id="consultar" class="btn btn-secondary fa fa-search nav-icon"  data-toggle="tooltip" title="Pesquisar" data-placement="top"></button>

                            {{-- btn-filtro-avançado --}}
                            <button type="button"
                                id="filtrar" data-toggle="tooltip"
                                title="Filtro avançado" data-placement="top"
                                class="btn btn-secondary fa fa-filter nav-icon" 
                                style="float: left; width: 40px;">
                                <i class="dropdown-toggle"></i>
                            </button>
                            
                            {{-- btn-cadastro --}}
                            <a href="#" disabled id="cadastro" class="btn btn-secondary" data-toggle="tooltip" title="Cadastrar" data-placement="top"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                    <div id="filtro_avancado" style="display: none; width:100%;">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option>Selecione</option>
                                        <option v-for="s in status">@{{s.nome}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="escala">Escala</label>
                                    <select id="escala" class="form-control">
                                        <option>Selecione</option>
                                        <option v-for="escala in escalas">@{{escala.vaga_id}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="unidade">Unidade</label>
                                    <select id="unidade" class="form-control">
                                        <option>Selecione</option>
                                        <option v-for="unidade in unidades">@{{unidade.unidade}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sala">Sala</label>
                                    <select id="sala" class="form-control">
                                        <option>Selecione</option>
                                        <option v-for="sala in salas">@{{sala.nome}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="semana">Semana</label>
                                    <select id="semana" class="form-control">
                                        <option>Selecione</option>
                                        <option>domingo</option>
                                        <option>segunda-feira</option>
                                        <option>terça-feira</option>
                                        <option>quarta-feira</option>
                                        <option>quinta-feira</option>
                                        <option>sexta-feira</option>
                                        <option>sábado</option>
                                    </select>
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="periodo">Período</label>
                                    <select id="periodo" class="form-control">
                                        <option value="">Selecione</option>
                                        <option v-for="periodo in periodos">@{{periodo}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="horario">Horário</label>
                                    <select id="horario" class="form-control">
                                        <option value="">Selecione</option>
                                        <option v-for="horario in horarios">@{{horario}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="recorrencia">Recorrência</label>
                                    <select id="recorrencia" class="form-control">
                                        <option value="">Selecione</option>                                        
                                        <option v-show="recorrencias == 'S'">Semanal</option>
                                        <option v-show="recorrencias == 'M'">Mensal</option>
                                        <option v-show="recorrencias == 'Q'">Quinzenal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="especialidade">Especialidade</label>
                                    <select id="especialidade" class="form-control">
                                        <option value="">Selecione</option>
                                        <option v-for="especialidade in especialidades">@{{especialidade.especialidade}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="medico">Médico</label>
                                    <select id="medico" class="form-control">
                                        <option value="">Selecione</option>
                                        <option v-for="medico in medicos">@{{medico.medico}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <table class="table table-striped table-hover">
                    <thead class="tbl-cabecalho">
                        <tr>
                            <th scope="col"><strong>Status</strong></th>
                            <th scope="col"><strong>Escala</strong></th>
                            <th scope="col"><strong>Unidade</strong></th>
                            <th scope="col"><strong>Sala</strong></th>
                            <th scope="col"><strong>Semana</strong></th>
                            <th scope="col"><strong>Período</strong></th>
                            <th scope="col"><strong>Horário</strong></th>
                            <th scope="col"><strong>Recorrência</strong></th>
                            <th scope="col"><strong>Carga</strong></th>
                            <th scope="col"><strong>Especialidade</strong></th>
                            <th scope="col"><strong>Médico</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plantoes as $plantao)
                            <tr class="dados">
                                <td scope="row" class="clickable" data-id="{{$plantao->vaga_id}}">
                                    {{$plantao->status}}  
                                </td>
                                <td scope="row" class="clickable" data-id="{{$plantao->vaga_id}}">
                                    #000{{$plantao->vaga_id}}
                                </td>
                                <td scope="row" class="clickable" data-id="{{$plantao->vaga_id}}">
                                    {{$plantao->unidade}}
                                </td>
                                <td scope="row" class="clickable" data-id="{{$plantao->vaga_id}}">
                                    {{$plantao->sala}}
                                </td>
                                <td scope="row" class="clickable" data-id="{{$plantao->vaga_id}}">
                                    {{$plantao->diaSemanaFormatada()}}
                                </td>
                                <td scope="row" class="clickable" data-id="{{$plantao->vaga_id}}">
                                    {{$plantao->dataInicioFormatada()}} - {{$plantao->dataTerminoFormatada()}}
                                </td>
                                <td scope="row" class="clickable" data-id="{{$plantao->vaga_id}}">
                                    {{$plantao->dataInicioSoHoraFormatada()}} - {{$plantao->dataTerminoSoHoraFormatada()}}                                    
                                </td>
                                <td scope="row" class="clickable" data-id="{{$plantao->vaga_id}}">
                                    {{$plantao->recorrenciaResultado()}}                                    
                                </td>
                                <td scope="row" class="clickable" data-id="{{$plantao->vaga_id}}">
                                    {{$plantao->cargaHorariaPlantao()}}                                    
                                </td>
                                <td scope="row" class="clickable" data-id="{{$plantao->vaga_id}}">
                                    {{$plantao->especialidade}}                                    
                                </td>
                                <td scope="row" class="clickable" data-id="{{$plantao->vaga_id}}">
                                    {{$plantao->medico}}                                    
                                </td>
                            </tr>                        
                        @endforeach
                    </tbody>
                </table>
                @if (count($plantoes) > 10)                    
                    {{$plantoes->links()}}
                @endif
            </form>
        </div>
    </div>
</div>
<script>

    new Vue({
        el: '#filtro_avancado',
        data: {
            plantoes: "{{ isset($plantoes) ? true : false }}",
            status: [],
            escalas: [],
            salas: [],
            unidades: [],
            especialidades: [],
            recorrencias: [],
            medicos: [],
            datasHoras: [],
            periodos: "",
            horarios: ""
        },
        methods: {
            buscarEscalas(){
                this.$http.get("{{route('plantao.escalas')}}").then(function(res){
                    this.escalas = res.body;
                });
            },
            buscarStatus(){
                this.$http.get("{{route('plantao.status')}}").then(function(res){
                    this.status = res.body;
                });
            },
            buscarSalas(){
                this.$http.get("{{route('plantao.salas')}}").then(function(res){
                    this.salas = res.body;                   
                });
            },
            buscarUnidades(){
                this.$http.get("{{route('plantao.unidades')}}").then(function(res){
                    this.unidades = res.body;                    
                });
            },
            buscarEspecialidades(){
                this.$http.get("{{route('plantao.especialidades')}}").then(function(res){
                    this.especialidades = res.body;                   
                });
            },
            buscarRecorrencias(){
                this.$http.get("{{route('plantao.recorrencias')}}").then(function(res){
                    var dados = res.body;
                    for(var i=0; i< dados.length;i++){ this.recorrencias = dados[i].recorrencia; }                                       
                });
            },
            buscarMedicos(){
                this.$http.get("{{route('plantao.medicos')}}").then(function(res){
                    this.medicos = res.body;
                });
            },
            horariosUnicos(value, index, self) { 
                return self.indexOf(value) === index;
            },
            buscarDatasEHorasPlantoes() {
                this.$http.get("{{route('plantao.data-hora')}}").then(function(res){
                    
                    this.datasHoras = res.body;                    
                    
                    var periodos = [];
                    var horarios = [];

                    for (var i = 0; i < this.datasHoras.length; i++) {
                        var dataInicio = new Date(this.datasHoras[i].data_inicio).toLocaleDateString();
                        var dataTermino = new Date(this.datasHoras[i].data_termino).toLocaleDateString();
                        
                        var horaInicio = new Date(this.datasHoras[i].data_inicio).toLocaleTimeString();
                        var horaTermino = new Date(this.datasHoras[i].data_termino).toLocaleTimeString();

                        periodos.push(dataInicio+" - "+dataTermino)
                        horarios.push(horaInicio+" - "+horaTermino)
                    }
                    
                    this.periodos = periodos;
                    this.horarios = horarios.filter(this.horariosUnicos);
                    
                });
            }
        },
        mounted: function(){
            if (this.plantoes) {
                this.buscarEscalas();
                this.buscarStatus();
                this.buscarSalas();
                this.buscarUnidades();
                this.buscarEspecialidades();
                this.buscarRecorrencias();
                this.buscarMedicos();
                this.buscarDatasEHorasPlantoes()
            }
        }
    });

    $(function(){

        $("#consultar").click(function(){
            $('#busca').submit();
        });

        $(".clickable").click(function() {            
            window.location.href = "{{route('vaga.acompanhamento', ['id' => ''])}}/" + $(this).data('id')
        });

        $('#filtrar').click(function(event) {
            var divFiltro = $('#filtro_avancado');
            divFiltro.toggle();
            event.stopPropagation;            
        });

        $("#filtro").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".dados").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $('#status, #escala, #unidade, #sala, #semana, #periodo, #horario, #recorrencia, #especialidade, #medico n').on('change', function() {            
            var status = $('select#status option:selected').text();
            var escala = $('select#escala option:selected').text();
            var unidade = $('select#unidade option:selected').text();
            var sala = $('select#sala option:selected').text();
            var semana = $('select#semana option:selected').text();
            var periodo = $('select#periodo option:selected').text();
            var horario = $('select#horario option:selected').text();
            var recorrencia = $('select#recorrencia option:selected').text();
            var especialidade = $('select#especialidade option:selected').text();
            var medico = $('select#medico option:selected').text();
            
            $('.dados').each(function() {
                if(status != 'Selecione'){
                    var nome = $(this).text().toUpperCase()
                                    .indexOf(' '+status.toUpperCase());
                } else if(escala != 'Selecione'){
                    var nome = $(this).text().toUpperCase()
                                    .indexOf(' '+escala.toUpperCase());
                } else if(unidade != 'Selecione'){
                    var nome = $(this).text().toUpperCase()
                                    .indexOf(' '+unidade.toUpperCase());
                } else if(sala != 'Selecione'){
                    var nome = $(this).text().toUpperCase()
                                    .indexOf(' '+sala.toUpperCase());
                } else if(semana != 'Selecione'){
                    var nome = $(this).text().toUpperCase()
                                    .indexOf(' '+semana.toUpperCase());
                } else if(periodo != 'Selecione'){
                    var nome = $(this).text().toUpperCase()
                                    .indexOf(' '+periodo.toUpperCase());
                } else if(horario != 'Selecione'){
                    var nome = $(this).text().toUpperCase()
                                    .indexOf(' '+horario.toUpperCase());
                } else if(recorrencia != 'Selecione'){
                    var nome = $(this).text().toUpperCase()
                                    .indexOf(' '+recorrencia.toUpperCase());
                } else if(especialidade != 'Selecione'){
                    var nome = $(this).text().toUpperCase()
                                    .indexOf(' '+especialidade.toUpperCase());
                } else if(medico != 'Selecione'){
                    var nome = $(this).text().toUpperCase()
                                    .indexOf(' '+medico.toUpperCase());
                }
                if (nome < 0) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        })

    });
</script>

@endsection