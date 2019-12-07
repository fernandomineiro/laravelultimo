@extends('layouts.app')

@section('content')
    
<div class="card" id="app">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><strong>Prospect</strong></li>
            </ol>
        </nav>
        <div id="registros">             
            <form id="busca" method="post" action="{{route('prospect.listar')}}">
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
                            <a href="{{route('prospect')}}" id="cadastro" class="btn btn-secondary" data-toggle="tooltip" title="Cadastrar" data-placement="top"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div id="filtro_avancado" class="form-group row" style="display: none;">
                    <label for="sel-status" class="col-sm-2">Status</label>
                    <div class="col-md-12">
                        <select id="sel-status" class="form-control">
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-default fa fa-search" id="btn-filtrar">Filtrar</button>
                </div>
                <table class="table table-striped table-hover">
                    <thead class="tbl-cabecalho">
                        <tr>
                            <th style="width: 1px;">
                                <input type="checkbox" id="chkTodos">
                            </th>
                            <th scope="col"><strong>Nome</strong></th>
                            <th scope="col"><strong>Telefone</strong></th>
                            <th scope="col"><strong>Ramal</strong></th>
                            <th scope="col"><strong>Status</strong></th>
                            <th scope="col"><strong>Ativo</strong></th>
                        </tr>
                    </thead>         
                    <tbody>
                        @foreach ($prospect as $prospect)
                            <tr class="dados">
                                <td scope="row">
                                    <input type="checkbox" name="chkProspect[]" class="chkProspect" value="{{$prospect->id}}">
                                </td>
                                <td class="clickable" data-id="{{$prospect->id}}">{{$prospect->nome}}</td>
                                <td class="clickable" data-id="{{$prospect->id}}">
                                    @if ($prospect->telefone1 == null)
                                        {{$prospect->telefone2}}
                                    @else
                                        {{$prospect->telefone1}}
                                    @endif
                                </td>
                                <td class="clickable" data-id="{{$prospect->id}}">
                                    @if ($prospect->ramal1 == null)
                                        {{$prospect->ramal2}}
                                    @else
                                        {{$prospect->ramal1}}
                                    @endif
                                </td>
                                <td class="clickable" data-id="{{$prospect->id}}">
                                    @foreach ($status as $s)
                                        @if ($prospect->idstatus_prospect == $s->id)
                                            {{$s->nome}}                                       
                                        @endif
                                    @endforeach
                                </td>
                                @if ($prospect->ativo == 'A')
                                    <td class="clickable" data-id="{{$prospect->id}}">Ativo</td>
                                @elseif ($prospect->ativo == 'I')
                                    <td class="clickable" data-id="{{$prospect->id}}">Inativo</td>
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
    // Ajax para seleção de status no filtro avançado:
    function loadStatus() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var arrayStatus = JSON.parse(this.responseText);
                var select = document.getElementById("sel-status");
                arrayStatus.forEach(function(status) {
                    var option = document.createElement('option');
                    option.setAttribute('value', status['id']);
                    option.innerText = status['nome'];
                    select.append(option);
                });
            }
        };
        xhttp.open("GET", "{{route('prospect.status.buscar')}}", true);
        xhttp.send();
    }
</script>
<script>
    $(function(){

        loadStatus(); 

        $("#consultar").click(function() {            
            $('#busca').submit();
        });

        $(".clickable").click(function() {            
            window.location.href = "{{route('prospect.editar', ['id' => ''])}}/" + $(this).data('id')
        });

        $('#filtrar').click(function(event) {
            var divFiltro = $('#filtro_avancado');
            divFiltro.toggle();
            event.stopPropagation;            
        });

        $('#btn-filtrar').on('click', function() {            
            var nomeSelecionado = $('#sel-status option:selected').text()
            $('.dados').each(function() {
                var nome = $(this).text().toUpperCase()
                                .indexOf(' '+nomeSelecionado.toUpperCase());
                if (nome < 0) {
                    $(this).fadeOut();
                } else {
                    $(this).fadeIn();
                }
            console.log(nomeSelecionado);
            });
        })

        $('#chkTodos').change(function(){
            var status = this.checked;
            $('.chkProspect').each(function(){
                this.checked = status;
            });
        })

    });
</script>

@endsection