@extends('layouts.app')

@section('content')
    
<div class="card" id="app">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><strong>F.A.Q.</strong></li>
            </ol>
        </nav>
        <div id="registros">             
            <form id="busca" method="post" action="{{route('faq.listar')}}">
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
                            
                            {{-- btn-status --}}
                            <button type="button" id="status" class="btn btn-secondary dropdown-toggle-split" title="Ações" data-placement="top" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-check"></i>
                                <i class="dropdown-toggle"></i>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                <input type="submit" class="dropdown-item" name="acao" value="Ativar">
                                <input type="submit" class="dropdown-item" name="acao" value="Inativar">
                                <input type="submit" class="dropdown-item" name="acao" value="Remover">
                            </div>

                            {{-- btn-cadastro --}}
                            <a href="{{route('faq.cadastro')}}" id="cadastro" class="btn btn-secondary" data-toggle="tooltip" title="Cadastrar" data-placement="top"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                    <div id="filtro_avancado" style="display: none;">
                        <div class="form-row">
                            <label for="filtro-visibilidade" class="col-form-label-md" style="padding: 25px 25px 0 0;">Visibilidade</label>
                            <div class="col-md-9">
                                <select name="filtro-avancado" id="filtro-visibilidade" class="form-control" v-model="selectedVisibilidade">
                                    <option>Selecione</option>
                                    <option>Geral</option>
                                    <option>Doctor Service</option>
                                    <option>Operadora</option>
                                    <option>Médicos</option>
                                    <option>Web</option>
                                    {{-- <option v-for="v in visibilidade" :value="v.id">@{{v.nome}}</option> --}}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            
                <table class="table table-responsive-md table-striped table-hover">
                    <thead class="tbl-cabecalho">
                        <tr>
                            <th style="width: 1px;">
                                <input type="checkbox" id="chkTodos">
                            </th>
                            <th scope="col"><strong>Visibilidade</strong></th>
                            <th scope="col"><strong>Pergunta</strong></th>
                            <th scope="col"><strong>Status</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($faqs as $faq)
                            <tr class="dados" draggable="true">
                                <td scope="row">
                                    <input type="checkbox" name="chkFaq[]" class="chkFaq" value="{{$faq->id}}">
                                </td>
                                <td style="width: 20%;" class="clickable" data-id="{{$faq->id}}">
                                    @if ($faq->modulo != null)
                                        {{$faq->modulo}}
                                    @else
                                        Geral                                   
                                    @endif
                                </td>
                                <td scope="row" class="clickable" data-id="{{$faq->id}}">
                                    {{$faq->pergunta}}
                                </td>
                                <td style="width: 25%;">
                                    <span class="clickable" data-id="{{$faq->id}}">{{($faq->ativo == 'A') ? 'Ativo' : 'Inativo'}}</span>

                                    <button type="submit" value="{{$faq->id}}" id="down" class="btn btn-default btn-sm btn-ordem fa fa-chevron-down" v-on:click="moverParaBaixo({{$faq->id}})"></button>

                                    <button type="submit" value="{{$faq->id}}" id="up" class="btn btn-default btn-sm btn-ordem fa fa-chevron-up" v-on:click="moverParaCima({{$faq->id}})"></button>
                                </td>
                            </tr>                        
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
    new Vue({
        el: '#busca',
        data: {
            visibilidade: null,
            selectedVisibilidade: 'Selecione'
        },
        methods: {
            buscarModulos(){
                this.$http.get("{{route('faq.modulos')}}").then(function(res){
                    this.visibilidade = res.data;
                });
            },
            moverParaCima(id){
                this.$http.get("{{route('faq.acima', ['id' => ''])}}/"+id).then(function (res) {
                    console.log(res.data);
                });
            },
            moverParaBaixo(id){
                this.$http.get("{{route('faq.abaixo', ['id' => ''])}}/"+id).then(function (res) {
                    console.log(res.data);
                });
            }
        },
        mounted: function(){
            this.buscarModulos();
        }
    });
</script>
<script>
    
    $(function(){

        $("#consultar").click(function(){
            $('#busca').submit();
        });

        $(".clickable").click(function() {            
            window.location.href = "{{route('faq.editar', ['id' => ''])}}/" + $(this).data('id')
        });

        $('#filtrar').click(function(event) {
            var divFiltro = $('#filtro_avancado');
            divFiltro.toggle();
            event.stopPropagation;            
        });

        $('#filtro-visibilidade').on('change', function() {            
            var visibilidade = $('#filtro-visibilidade option:selected').text();
            
            $('.dados').each(function() {
                if(visibilidade != 'Selecione'){
                    var nome = $(this).text().toUpperCase()
                                .indexOf(' '+visibilidade.toUpperCase());
                }
                if (nome < 0) {
                    $(this).fadeOut();
                } else {
                    $(this).fadeIn();
                }
            });
        })
        
        $('#chkTodos').change(function(){
            var status = this.checked;
            $('.chkFaq').each(function(){
                this.checked = status;
            });
        })

    });
</script>

@endsection