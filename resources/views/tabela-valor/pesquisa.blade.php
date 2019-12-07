@extends('layouts.app')

@section('content')
    
<div class="card" id="app">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><strong>Tabelas de Valores</strong></li>
            </ol>
        </nav>
        <div id="registros"> 
            <form id="busca" method="post" action="{{route('tabela-valor.listar')}}">
                @csrf
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>	
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
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
                            <button type="button" id="status" class="btn btn-secondary dropdown-toggle-split" data-toggle="dropdown" title="Ações" data-placement="top" aria-haspopup="true" aria-expanded="false">
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
                            <a href="{{route('tabela-valor')}}" id="cadastro" class="btn btn-secondary" data-toggle="tooltip" title="Cadastrar" data-placement="top"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row" id="filtro_avancado" style="display: none;">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="sel-unidade">Unidade</label>
                            <select id="sel-unidade" class="form-control">
                                <option>Selecione</option>
                                <option value="">Geral</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="sel-unidade">Status</label>
                            <select id="sel-status" class="form-control">
                                <option>Selecione</option>
                                <option>Ativo</option>
                                <option>Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead class="tbl-cabecalho">
                        <tr>
                            <th style="width: 1px;">
                                <input type="checkbox" id="chkTodos">
                            </th>
                            <th scope="col"><strong>Unidade</strong></th>
                            <th scope="col"><strong>Nome</strong></th>
                            <th scope="col"><strong>Expira em</strong></th>
                            <th scope="col"><strong>Status</strong></th>
                        </tr>
                    </thead>         
                    <tbody>
                        @foreach ($tabelas as $tabela)
                            <tr class="dados">
                                <td scope="row">
                                    <input type="checkbox" name="chkTabela[]" class="chkTabela" value="{{$tabela->id}}">
                                </td>
                                <td class="clickable" data-id="{{$tabela->id}}">
                                    @if ($tabela->unidade != null)
                                        {{$tabela->unidade}}
                                    @else
                                        Geral                                   
                                    @endif
                                </td>
                                <td class="clickable" data-id="{{$tabela->id}}">
                                    {{$tabela->nome}}
                                </td>
                                <td class="clickable" data-id="{{$tabela->id}}">
                                    {{date("d/m/Y H:i", str_replace("-", "/", strtotime($tabela->expira)))}}
                                </td>
                                @if ($tabela->status == 'A')
                                    <td class="clickable" data-id="{{$tabela->id}}">Ativo</td>
                                @elseif ($tabela->status == 'I')
                                    <td class="clickable" data-id="{{$tabela->id}}">Inativo</td>
                                @endif
                            </tr>                        
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('.clickable, .dropdown-item, #consultar, #cadastro').click(function() {
            $('#loading').show();
        })
        setTimeout(() => {
            $("#loading").hide();    
        }, 500);

        $("#consultar").click(function() {            
            $('#busca').submit();
        });

        $('#filtrar').click(function(event) {
            var divFiltro = $('#filtro_avancado');
            divFiltro.toggle();
            event.stopPropagation;            
        });

        $(".clickable").click(function() {            
            window.location.href = "{{route('tabela-valor.editar', ['id' => ''])}}/" + $(this).data('id')
        });

        $.get("{{route('tabela-valor.unidades')}}", function(data) {
            $.each(data, function(index) {
                $('#sel-unidade').append("<option value='" + data[index].id + "'>" + data[index].nome + "</option>");
            })
        })

        $('#sel-unidade, #sel-status').on('change', function() {            
            var unidade = $('#sel-unidade option:selected').text();            
            var status = $('#sel-status option:selected').text();            
            $('.dados').each(function() {

                if(unidade != 'Selecione'){
                    var nome = $(this).text().toUpperCase().indexOf(' '+unidade.toUpperCase());
                } 
                else if(status != 'Selecione'){
                    var nome = $(this).text().toUpperCase().indexOf(' '+status.toUpperCase());
                }

                if (nome < 0) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        })
    })
</script>

@endsection