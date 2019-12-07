@extends('layouts.app')

@section('content')
    
<div class="card" id="app">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><strong>Banco</strong></li>
            </ol>
        </nav>
        <div id="registros">             
            <form id="busca" method="post" action="{{route('banco.listar')}}">
                @csrf     
                <div id="form-acoes" class="form-group">
                    {{-- Campo de pesquisa --}}
                    <div class="input-group">
                        <input type="text" name="filtro" id="filtro" class="form-control form-control-md" placeholder="Filtro">
                        <div class="input-group-append">

                            {{-- btn-consultar --}}
                            <button type="button" id="consultar" class="btn btn-secondary fa fa-search nav-icon" data-toggle="tooltip" title="Pesquisar" data-placement="top"></button>

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
                            <a href="{{route('banco')}}" id="cadastro" class="btn btn-secondary" title="Cadastrar" data-placement="top"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-responsive-sm">
                    <thead class="tbl-cabecalho">
                        <tr>
                            <th><input type="checkbox" id="chkTodos"></th>
                            <th scope="col"><strong>Número</strong></th>
                            <th scope="col"><strong>Nome</strong></th>
                            <th scope="col"><strong>Status</strong></th>
                        </tr>
                    </thead>
                    <tbody>  
                        @foreach ($banco as $b)
                            <tr>
                                <td style="width: 1px;">
                                    <input type="checkbox" name="chkBanco[]"  class="chkBanco" value="{{$b->id}}">
                                </td>
                                <td class="clickable" data-id="{{$b->id}}">{{$b->numero}}</td>
                                <td class="clickable" data-id="{{$b->id}}">{{$b->nome}}</td>
                                @if ($b->ativo == 'A')
                                    <td class="clickable" data-id="{{$b->id}}">Ativo</td>
                                @else
                                    <td class="clickable" data-id="{{$b->id}}">Inativo</td>
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

        $(".clickable").click(function() {            
            window.location.href = "{{url('/')}}/banco/editar/" + $(this).data('id')
        });
        
        $('#chkTodos').change(function(){
            var status = this.checked;
            $('.chkBanco').each(function(){
                this.checked = status;
            });
        })

        $("#consultar").click(function() {            
            $('#busca').submit();
        });

    });
</script>

@endsection