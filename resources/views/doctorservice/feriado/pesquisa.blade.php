@extends('layouts.app')

@section('content')
    
<div class="card" id="app">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><strong>Feriado</strong></li>
            </ol>
        </nav>
        <div id="registros">             
            <form id="busca" method="post" action="{{route('feriado.listar')}}">
                @csrf
                <div id="form-acoes" class="form-group">
                    {{-- Campo de pesquisa --}}
                    <div class="input-group">
                        <input type="text" name="filtro" id="filtro" class="form-control form-control-md" placeholder="Filtro">
                        <div class="input-group-append">

                            {{-- btn-consultar --}}
                            <button type="button" id="consultar" class="btn btn-secondary fa fa-search nav-icon" title="Pesquisar" data-placement="top"></button>

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
                            <a href="{{route('feriado')}}" id="cadastro" class="btn btn-secondary"><i class="fa fa-plus" data-toggle="tooltip" title="Cadastrar" data-placement="top"></i></a>
                        </div>
                    </div>
                </div>
            
                <table class="table table-striped table-hover">
                    <thead class="tbl-cabecalho">
                        <tr>
                            <th style="width: 1px;"><input type="checkbox" id="chkTodos"></th>
                            <th style="width: 17%;"><strong>UF</strong></th>
                            <th style="width: 30%;"><strong>Nome</strong></th>
                            <th style="width: 1px;"><strong>Dia</strong></th>
                            <th style="width: 5px;"><strong>Mês</strong></th>
                            <th style="width: 5px;"><strong>Ano</strong></th>
                            <th scope="col"><strong>Dia Semana</strong></th>
                            <th scope="col"><strong>Núm. Semana</strong></th>
                            <th scope="col"><strong>Status</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feriados as $feriado)
                            <tr class="dados">
                                <td scope="row">
                                    <input type="checkbox" name="chkFeriados[]" class="chkFeriados" value="{{$feriado->id}}">
                                </td>
                                <td class="clickable" data-id="{{$feriado->id}}">
                                    @foreach ($estados as $estado)
                                        @if ($feriado->idestado == $estado->id)
                                            {{$estado->estado}}
                                        @endif
                                    @endforeach
                                </td>
                                <td class="clickable" data-id="{{$feriado->id}}">{{$feriado->nome}}</td>
                                <td class="clickable" data-id="{{$feriado->id}}">{{$feriado->dia}}</td>
                                @foreach ($meses as $chave=>$valor)                                    
                                    @if ($feriado->mes == $chave)
                                        <td class="clickable" data-id="{{$feriado->id}}">{{$valor}}</td>                           
                                    @endif                                    
                                @endforeach
                                <td class="clickable" data-id="{{$feriado->id}}">{{$feriado->ano}}</td>
                                @foreach ($dias_semana as $chave=>$valor)                                   
                                    @if ($feriado->dia_semana == $chave)
                                        <td class="clickable" data-id="{{$feriado->id}}">{{$valor}}</td>
                                    @endif
                                @endforeach
                                @foreach ($num_semana as $chave=>$valor)
                                    @if ($feriado->num_semana == $chave)
                                        <td class="clickable" data-id="{{$feriado->id}}">{{$valor}}</td>
                                    @endif                                    
                                @endforeach
                                @if ($feriado->ativo == 'A')
                                    <td class="clickable" data-id="{{$feriado->id}}">Ativo</td>
                                @elseif ($feriado->ativo == 'I')
                                    <td class="clickable" data-id="{{$feriado->id}}">Inativo</td>
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

        $("#consultar").click(function() {            
            $('#busca').submit();
        });

        $(".clickable").click(function() {            
            window.location.href = "{{url('/')}}/feriado/editar/" + $(this).data('id')
        });
        
        $('#chkTodos').change(function(){
            var status = this.checked;
            $('.chkFeriados').each(function(){
                this.checked = status;
            });
        })

    });
</script>

@endsection