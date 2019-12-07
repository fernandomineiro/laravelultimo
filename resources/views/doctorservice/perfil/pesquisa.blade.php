@extends('layouts.app')

@section('content')
    <div class="card" id="app">
        <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><strong>Perfil</strong></li>
                </ol>
            </nav>
            <div id="registros">
                <form id="busca" method="post" action="{{route('perfil.listar')}}">
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
                                <a href="{{route('perfil')}}" id="cadastro" class="btn btn-secondary" title="Cadastrar" data-placement="top"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                    </div>

                    <table class="table table-striped table-responsive-sm">
                        <thead class="tbl-cabecalho">
                        <tr>
                            <th><input type="checkbox" id="chkTodos"></th>
                            <th scope="col"><strong>Módulo</strong></th>
                            <th scope="col"><strong>Perfil</strong></th>
                            <th scope="col"><strong>Statis</strong></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($perfil as $p)
                            <tr>
                                <td scope="row" style="width: 1px;">
                                    <input type="checkbox" name="chkPerfil[]" class="chkPerfil" value="{{$p->id}}">
                                </td>
                                <td scope="row" class="clickable" data-id="{{$p->id}}">{{$p->modulo}}</td>
                                <td scope="row" class="clickable" data-id="{{$p->id}}">{{$p->nome}}</td>
                                <td scope="row" class="clickable" data-id="{{$p->id}}">{{$p->ativo == 'A' ? 'Ativo' : 'Inativo'}} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        $("#chkTodos").click(function() {
            
            if($('#chkTodos').is(':checked')){

                $('.chkPerfil').attr('checked', true);
            }else{

                $('.chkPerfil').attr('checked', false);
            }
        });

        $(".clickable").click(function() {
            
            window.location.href = "{{route('perfil.editar',['id' => ''])}}/" + $(this).data('id')
        });

        $("#consultar").click(function() {
            
            $('#busca').submit();
        });
    });
</script>
@endsection