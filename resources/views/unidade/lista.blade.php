
    <div class="@if(!isset($bread)) card @endif" id="app">
        <div class="@if(!isset($bread)) card-body @endif">
            <div class="tab-pane">
                @if(!isset($bread))
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><strong>Unidade</strong></li>
                        </ol>
                    </nav>
                @endif
                <div id="registros">
                    <form id="buscar-banco" method="post" action="{{route('unidade.listar')}}">
                        @csrf
                        <div id="form-acoes" class="form-group">
                            {{-- Campo de pesquisa --}}
                            <div class="input-group">
                                <input type="text" name="filtro" id="filtro" class="form-control form-control-md" placeholder="Filtro">
                                <div class="input-group-append">

                                    {{-- btn-consultar --}}
                                    <button type="button" id="consultar" class="btn btn-secondary fa fa-search nav-icon"  data-toggle="tooltip" title="Pesquisar" data-placement="top"></button>

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
                                    <a href="{{route('unidade')}}" id="cadastro" class="btn btn-secondary" data-toggle="tooltip" title="Cadastrar" data-placement="top"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        <table class="table table-striped table-responsive-sm">
                            <thead class="tbl-cabecalho">
                            <tr>
                                <th><input type="checkbox" id="chkTodos"></th>
                                <th scope="col"><strong>Nome</strong></th>
                                <th scope="col"><strong>Telefone</strong></th>
                                <th scope="col"><strong>Cidade</strong></th>
                                <th scope="col"><strong>UF</strong></th>
                                <th scope="col"><strong>Fotos</strong></th>
                                <th scope="col"><strong>Status</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($unidades as $unidade)
                                <tr>
                                    <td scope="row">
                                        <input type="checkbox" name="chkBanco[]" class="chkUnidade" value="{{$unidade->id}}">
                                    </td>
                                    <td scope="row" class="clickable-unidade" data-id="{{$unidade->id}}">{{$unidade->nome}} </td>
                                    <td scope="row" class="clickable-unidade" data-id="{{$unidade->id}}" id="telefone">{{$unidade->telefone}} </td>
                                    <td scope="row" class="clickable-unidade" data-id="{{$unidade->id}}">{{$unidade->cidade}} </td>
                                    <td scope="row" class="clickable-unidade" data-id="{{$unidade->id}}">{{$unidade->estado}} </td>
                                    <td scope="row" class="clickable-unidade" data-id="{{$unidade->id}}">{{$unidade->fotos}} </td>
                                    <td scope="row" class="clickable-unidade" data-id="{{$unidade->id}}">{{$unidade->status == 'A' ? 'Ativo' : 'Inativo'}} </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>


<script>
    $(document).ready(function(){

        $("#chkTodos").click(function() {
            
            if($('#chkTodos').is(':checked')){

                $('.chkUnidade').attr('checked', true);
            }else{

                $('.chkUnidade').attr('checked', false);
            }
        });

        $(".clickable-unidade").click(function() {
            
            window.location.href = "{{route('unidade.alterar',['id' => ''])}}/" + $(this).data('id')
        });

        $("#consultar").click(function() {
            
            $('form').submit();
        });
        $('td[id^=telefone').each(function() {
            $(this).mask('(00) 00000-0000');
        });
    });
</script>
