@extends('layouts.app')

@section('content')
    <div class="card" id="app">
        <div class="card-body">
            <div class="tab-pane">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard Operadora</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><strong>Parametro</strong></li>
                    </ol>
                </nav>
                <div id="registros">
                    <form id="busca" method="post" action="{{route('parametro.listar')}}">
                        @csrf
                        <div id="form-acoes" class="form-group">
                            {{-- Campo de pesquisa --}}
                            <div class="input-group">
                                <input type="text" name="filtro" id="filtro" class="form-control form-control-md" placeholder="Filtro">
                                <div class="input-group-append">

                                    {{-- btn-consultar --}}
                                    <button type="button" id="consultar" class="btn btn-secondary fa fa-search nav-icon" data-toggle="tooltip" title="Pesquisar" data-placement="top"></button>

                                    {{-- btn-filtro-avançado --}}
                                    <button type="button"
                                        id="filtrar" data-toggle="tooltip"
                                        title="Filtro avançado" data-placement="top"
                                        class="btn btn-secondary fa fa-filter nav-icon" 
                                        style="float: left; width: 40px;">
                                        <i class="dropdown-toggle"></i>
                                    </button>    
                                    {{-- btn-status --}}
                                    <button type="button" id="status" class="btn btn-secondary dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Ações" data-placement="top">
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
                                    <a href="{{route('parametro.registro')}}" id="cadastro" class="btn btn-secondary" title="Cadastrar" data-placement="top"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="filtro_avancado" style="display: none; width: 100%;">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="operadora">Unidade</label>
                                        <select id="operadora" class="form-control">
                                            <option value="">Selecione</option>
                                            @foreach($operadoraunidade as $uni)
                                                <option value="{{$uni->id}}">{{$uni->nome}}</option>
                                            @endforeach 
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        
                        <table class="table table-striped table-responsive-sm">
                            <thead class="tbl-cabecalho">
                            <tr>
                                <th><input type="checkbox" id="chkTodos"></th>
                                <th scope="col"><strong>Unidade</strong></th>
                                <th scope="col"><strong>Confirmacao (dias)</strong></th>
                                <th scope="col"><strong>Troca (dias)</strong></th>
                                <th scope="col"><strong>Cancelamento (dias)</strong></th>
                                <th scope="col"><strong>Disputa (dias)</strong></th>
                                <th scope="col"><strong>Checkpoint(dias)</strong></th>
                                
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($parametro as $para)
                                <tr data-id="{{$para->id}}">
                                <td scope="row" class="clickable-false">
                                        <input type="checkbox" name="chkBanco[]" class="chkOperadora clickable-false" value="{{$para->id}}">
                                    </td>
                                    
                                    <td class="clickable" scope="row">{{$para->unidade}} </td>
                                    <td class="clickable" scope="row">{{$para->confirmacao}} </td>
                                    <td class="clickable" scope="row">{{$para->troca}} </td>
                                    <td class="clickable" scope="row">{{$para->cancelamento}} </td>
                                    <td class="clickable" scope="row">{{$para->disputa}} </td>
                                    <td class="clickable" scope="row">{{$para->checkpoint}} </td>
                                   
                                    
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        $("#chkTodos").click(function() {
            
            if($('#chkTodos').is(':checked')){

                $('.chkOperadora').attr('checked', true);
            }else{

                $('.chkOperadora').attr('checked', false);
            }
        });

        $(".clickable").click(function() {



            window.location.href = "{{url('/')}}/parametro/alterar/" + $(this).parent().data('id');
        });

        $("#consultar").click(function() {
            
            $('#busca').submit();
        });

        $('#filtrar').click(function(event) {
            var divFiltro = $('#filtro_avancado');
            divFiltro.toggle();
            event.stopPropagation;            
        });

    });
</script>
@endsection
