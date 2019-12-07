@extends('layouts.app')

@section('content')
    
<div class="card" id="app">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><strong>Sala</strong></li>
            </ol>
        </nav>
        <div id="registros">             
            <form id="busca" method="post" action="{{route('sala.listar')}}">
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
                            <a href="{{route('sala.cadastro')}}" id="cadastro" class="btn btn-secondary" title="Cadastrar" data-placement="top"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="row" id="filtro_avancado" style="width: 100%; display: none;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unidade">Unidade</label>
                                <select id="unidade" class="form-control">
                                    <option value="">Selecione</option>
                                    @foreach($unidades as $unidade)
                                        <option value="{{$unidade->id}}">{{$unidade->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" class="form-control">
                                    <option value="">Selecione</option>
                                    <option value="A">Ativo</option>
                                    <option value="I">Inativo</option>
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
                            <th scope="col"><strong>Nome</strong></th>
                            <th scope="col"><strong>Status</strong></th>
                        </tr>
                    </thead>
                    <tbody>  
                        @foreach ($salas as $sala)
                            <tr class="dados">
                                <td style="width: 1px;">
                                    <input type="checkbox" name="chkSala[]"  class="chkSala" value="{{$sala->id}}">
                                </td>
                                <td class="clickable" data-id="{{$sala->id}}">{{$sala->unidade}}</td>
                                <td class="clickable" data-id="{{$sala->id}}">
                                    <div id="label-cor" style="background-color: {{$sala->cor}};"></div>
                                    {{$sala->sala}}
                                </td>
                                @if ($sala->ativo == 'A')
                                    <td class="clickable" data-id="{{$sala->id}}">Ativo</td>
                                @else
                                    <td class="clickable" data-id="{{$sala->id}}">Inativo</td>
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
            window.location.href = "{{route('sala.editar', ['id' => ''])}}/" + $(this).data('id')
        });

        $('#filtrar').click(function(event) {
            var divFiltro = $('#filtro_avancado');
            divFiltro.toggle();
            event.stopPropagation;            
        });
        
        $('#chkTodos').change(function(){
            var status = this.checked;
            $('.chkSala').each(function(){
                this.checked = status;
            });
        })

        $("#consultar").click(function() {            
            $('#busca').submit();
        });

        $('#unidade, #status').on('change', function() {            
            var unidade = $('#unidade option:selected').text();
            var status = $('select#status option:selected').text();     
            console.log(status)
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
        });

    });
</script>

@endsection