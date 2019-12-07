
    <div class="@if(!isset($bread)) card @endif" id="app">
        <div class="@if(!isset($bread)) card-body @endif">
            <div class="tab-pane">
                @if(!isset($bread))
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><strong>Operador</strong></li>
                        </ol>
                    </nav>
                @endif
                <div id="registros">
                    <form id="buscar-banco" method="post" action="{{route('operador.listar')}}">
                        @csrf
                        <div class="form-group">
                            {{-- Campo de pesquisa --}}
                            <div class="input-group">
                                <input type="text" name="filtro" id="filtro" class="form-control form-control-md" placeholder="Filtro" value="{{$filtro}}">

                            <div class="input-group-append">
                                {{-- btn-consultar --}}
                                    <button type="submit" id="consultar" class="btn btn-secondary fa fa-search nav-icon" data-toggle="tooltip" title="Pesquisar" data-placement="top"></button>

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
                                    <a href="{{route('operador')}}" id="cadastro" class="btn btn-secondary" data-toggle="tooltip" title="Cadastrar" data-placement="top"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                            {{-- filtro avancado --}}
                            <div class="row" id="filtro_avancado" style="display: none; width: 100%;">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="operadora">Operadora</label>
                                        <select id="operadora" class="form-control">
                                            <option value="">Selecione</option>
                                            @foreach($operadoras as $operadora)
                                                <option value="{{$operadora->id}}">{{$operadora->nome}}</option>
                                            @endforeach 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="perfil">Perfil</label>
                                        <select id="perfil" class="form-control">
                                            <option value="">Selecione</option>
                                            @foreach($perfis as $perfil)
                                                <option value="{{$perfil->id}}">{{$perfil->nome}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
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
                                <th scope="col"><strong>CPF</strong></th>
                                <th scope="col"><strong>Nome</strong></th>
                                <th scope="col"><strong>Operadora</strong></th>
                                <th scope="col"><strong>Unidade</strong></th>
                                <th scope="col"><strong>Perfil</strong></th>
                                <th scope="col"><strong>Status</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($operadores as $operador)
                                <tr class="dados">
                                    <td scope="row">
                                        <input type="checkbox" name="chkBanco[]" class="chkOperador" value="{{$operador->id}}">
                                    </td>
                                    <td scope="row" class="clickable" data-id="{{$operador->id}}" id="cpf">{{$operador->cpf}} </td>
                                    <td scope="row" class="clickable" data-id="{{$operador->id}}">{{$operador->nome}} </td>
                                    <td scope="row" class="clickable" data-id="{{$operador->id}}">{{$operador->operadora}} </td>
                                    <td scope="row" class="clickable" data-id="{{$operador->id}}">{{$operador->unidade}} </td>
                                    <td scope="row" class="clickable" data-id="{{$operador->id}}">{{$operador->perfil}} </td>
                                    <td scope="row" class="clickable" data-id="{{$operador->id}}">{{$operador->status == 'A' ? 'Ativo' : 'Inativo'}} </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>


@section('scripts')
<script>
    $(document).ready(function(){

        $(".clickable").click(function() {
            window.location.href = "{{route('operador.alterar', ['id' => ''])}}/" + $(this).data('id')
        });
        
        $("#chkTodos").click(function() {
            
            if($('#chkTodos').is(':checked')){

                $('.chkOperador').attr('checked', true);
            }else{

                $('.chkOperador').attr('checked', false);
            }
        });

        $("#consultar").click(function() {
            
            $('form').submit();
        });

        $('#filtrar').click(function(event) {
            var divFiltro = $('#filtro_avancado');
            divFiltro.toggle();
            event.stopPropagation;            
        });

        $('td[id^=cpf').each(function() {
            $(this).mask('000.000.000-00', {reverse: false});
        });

        $('#unidade, #operadora, #perfil, #status').on('change', function() {            
            var unidade = $('#unidade option:selected').text();            
            var operadora = $('#operadora option:selected').text();            
            var perfil = $('#perfil option:selected').text();            
            var status = $('select#status option:selected').text();     
            $('.dados').each(function() {

                if(unidade != 'Selecione'){
                    var nome = $(this).text().toUpperCase().indexOf(' '+unidade.toUpperCase());
                } 
                else if(operadora != 'Selecione'){
                    var nome = $(this).text().toUpperCase().indexOf(' '+operadora.toUpperCase());
                } 
                else if(perfil != 'Selecione'){
                    var nome = $(this).text().toUpperCase().indexOf(' '+perfil.toUpperCase());
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
    });
</script>
@endsection