@extends('layouts.app')

@section('content')
    <div class="card" id="app">
        <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tabela-valor.listar') }}">Tabelas de Valores</a></li>
                    @if (isCadastroTabela())                        
                        <li class="breadcrumb-item active" aria-current="page"><strong>Cadastro</strong></li>
                    @else    
                        <li class="breadcrumb-item active" aria-current="page"><strong>Edição</strong></li>
                    @endif
                </ol>
            </nav>
            <div class="row">
                <div class="col-12">
                    <div id="registros">
                        <form method="POST" {{ isset($tabela->id) ? 'action=' . route('tabela-valor.atualizar', ['id' => $tabela->id]) : 'action=' . route('tabela-valor.cadastrar') }}>
                            @if (!isCadastroTabela())
                                @method('PUT')
                            @endif
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                @if ($message = Session::get('error'))
                                <div class="alert alert-danger alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>	
                                        <strong>{{ $message }}</strong>
                                </div>
                                @endif
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unidade">Unidade:</label>
                                        <select name="unidade" v-model="unidade" class="form-control">
                                            <option value="">Geral</option>
                                            @foreach ($unidades as $unidade)
                                            <option value="{{$unidade->id}}">{{$unidade->nome}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="idoperadora" value="{{$unidade->idoperadora}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nome">Nome:</label>
                                    <input name="nome" type="text" v-model="nome" class="form-control" maxlength="100">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="descricao">Descrição:</label>
                                        <textarea name="descricao" v-model="descricao" class="form-control" cols="3" rows="3" maxlength="100"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expira_em">Expira Em:</label>
                                        <div class="col-md-12 input-group date" id="expira_em" data-target-input="nearest">
                                            <input name="expira_em" type="text" v-model="expira" class="form-control datetimepicker-input" data-target="#expira_em">
                                            <div class="input-group-append" data-target="#expira_em" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status:</label>
                                        <select name="status" class="form-control" v-model="status" v-on:change="ativarTabela()">
                                            <option value="A">Ativo</option>
                                            <option value="I">Inativo</option>
                                        </select>
                                    </div>
                                </div>            
                            </div>
                            <div class="form-group">
                                <div class="row" style="float: right;">
                                    <input type="submit" class="btn btn-primary" name="salvar" value="Salvar">
                                    @if (!isCadastroTabela())
                                    <input type="submit" class="btn btn-default" name="remover" value="remover">
                                    @endif
                                    <a href="{{ route('tabela-valor.listar') }}" class="btn btn-default" name="cancelar">Cancelar</a>
                                </div>
                            </div>
                            @if (!isCadastroTabela())
                            <div class="modal fade" id="modal-ativar-inativar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal-ativar-inativar">Confirmação</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            DESEJA MESMO ATIVAR A TABELA "{{$tabela->nome}}"?<br>
                                            Se você ativá-la, as outras tabelas ativas desta operadora serão inativadas!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" v-on:click="status = 'I'">Não</button>
                                            <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="status = 'A'">Sim</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if (!isCadastroTabela())
    <div class="row">
        <div class="col-12">
            @include('tabela-valor.valores')
        </div>
    </div>
    @endif
    
<script>
    var $j = jQuery.noConflict();
    $j(function() {

        setTimeout(() => {
            $j("#loading").hide();    
        }, 500);

        let isCadastro = "{{ isCadastroTabela() }}";
        if(!isCadastro){
            $j('[name=unidade]').prop('disabled', true);
        }        

        $j('#expira_em').datetimepicker({
            locale: 'pt-br'
        });
    })

    new Vue({
        el: '#app',
        data: {
            unidade: "{{ !empty($tabela->idoperadora_unidade) ? $tabela->idoperadora_unidade : old('unidade') }}",
            nome: "{{ !empty($tabela->nome) ? $tabela->nome : old('nome') }}",
            descricao: "{{ !empty($tabela->descricao) ? $tabela->descricao : old('descricao') }}",
            expira: "{{ !empty($tabela->expira) ? date('d/m/Y H:i', strtotime($tabela->expira)) : '' }}",
            status: "{{ !empty($tabela->status) ? $tabela->status : 'I' }}"
        },
        methods: {
            ativarTabela(){
                if (this.status == 'A') {
                    $j('#modal-ativar-inativar').modal({
                        show: true
                    })                    
                }
            }
        }
    })
</script>
@endsection
