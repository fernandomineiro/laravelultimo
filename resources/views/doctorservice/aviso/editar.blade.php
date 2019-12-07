@extends('layouts.app')

@section('content')

<div class="card" id="app">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('aviso.listar') }}">Aviso</a></li>
                <li class="breadcrumb-item active" aria-current="page"><strong>Edição</strong></li>
            </ol>
        </nav>
        <div id="registros">
            <form method="POST" action="{{route('aviso.atualizar', $aviso->id)}}">
                @method('PUT')
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Módulo:</label>
                    <div class="col-sm-10">
                        <select name="modulo" class="form-control" v-on:change="getOperadoras()">
                        <option value="">Geral</option>
                        @foreach ($modulos as $modulo)
                            <option value="{{$modulo->id}}" {{$aviso->idmodulo == $modulo->id ? 'selected' : ''}}>{{$modulo->nome}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Operadora:</label>
                    <div class="col-md-10">
                        <select name="operadora" v-model="operadora" class="form-control" v-on:change="getUnidades(operadora)">
                            <option value="">Selecione</option>
                            <option v-for="op in operadoras" :value="op.id">@{{ op.nome_fantasia }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Unidade:</label>
                    <div class="col-sm-10">
                        <select name="unidade" v-model="unidade" class="form-control" v-on:change="getGrupos(unidade)">
                            <option value="">Selecione</option>
                            <option v-for="uni in unidades" :value="uni.id">@{{ uni.nome }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Grupo:</label>
                    <div class="col-sm-10">
                        <select name="unidade" id="grupo_medico" v-model="grupo" class="form-control">
                            <option value="">Selecione</option>
                            <option v-for="grupo in grupos" :value="grupo.id">@{{grupo.nome}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Mensagem:</label>
                    <div class="col-sm-10">
                        <textarea name="mensagem" placeholder="Sua mensagem..." rows="3" class="form-control rounded-0">{{$aviso->mensagem}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">URL:</label>
                    <div class="col-md-10">
                        <input type="text" name="url" placeholder="http://www.exemplo.com" class="form-control" value="{{$aviso->url}}">
                        <p>Use http://</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="visivel1" class="col-md-2 col-form-label">Visivel:</label>
                    <div style="margin: 20px 15px;">
                        <input type="checkbox" name="visivel" id="visivel" {{$aviso->visivel == 1 ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Data:</label>
                    <div class="col-md-4 input-group date" id="data_hora_abertura" data-target-input="nearest">
                        <input name="data_hora_abertura" value="{{date("d/m/Y H:i", strtotime($aviso->data_hora_abertura))}}" type="text" class="form-control datetimepicker-input" data-target="#data_hora_abertura">
                        <div class="input-group-append" data-target="#data_hora_abertura" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Visível Até</label>
                    <div class="col-md-4 input-group date" id="data_hora_encerramento" data-target-input="nearest">
                        <input name="data_hora_encerramento" value="{{date("d/m/Y H:i", strtotime($aviso->data_hora_encerramento))}}"  type="text" class="form-control datetimepicker-input" data-target="#data_hora_encerramento">
                        <div class="input-group-append" data-target="#data_hora_encerramento" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status:</label>
                    <div class="col-sm-2">
                        <select name="status" class="form-control form-control-md">
                            <option value="A" {{($aviso->ativo == 'A')?'selected':''}}>Ativo</option>
                            <option value="I" {{($aviso->ativo == 'I')?'selected':''}}>Inativo</option>
                        </select>
                    </div>
                </div>
                <div style="float: right;">
                    <input type="submit" class="btn btn-primary" name="salvar" value="Salvar">
                    <input type="submit" class="btn btn-default" name="remover" value="Remover">
                    <a href="{{ route('aviso.listar') }}" class="btn btn-default" name="cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        $(function() {
            $('#data_hora_abertura').datetimepicker({
                locale: 'pt-br'
            });
            $('#data_hora_encerramento').datetimepicker({
                locale: 'pt-br'
            });
        });

        new Vue({
            el: '#app',
            data: {
                operadoras: '',
                operadora: "{{ $aviso->idoperadora != '' ? $aviso->idoperadora : '' }}",
                unidades: '',
                unidade: "{{ $aviso->idoperadora_unidade != '' ? $aviso->idoperadora_unidade: '' }}",
                grupos: '',
                grupo: "{{ $aviso->idoperadora_grupo_medico != '' ? $aviso->idoperadora_grupo_medico : '' }}"
            },
            methods: {
                getOperadoras(){
                    this.$http.get("{{route('aviso.operadoras')}}").then(function(res) {
                        this.operadoras = res.data;
                    });
                },
                getUnidades(operadora){
                    this.$http.get("{{route('aviso.unidade', ['id' => ''])}}/" + this.operadora).then(function(res) {
                        this.unidades = res.data;
                        console.log(this.unidade);                        
                    })
                },
                getGrupos(unidade){
                    this.$http.get("{{route('aviso.grupo', ['id' => ''])}}/" + this.unidade).then(function(res) {
                        this.grupos = res.data;
                    })
                }
            },
            mounted: function(){
                this.getOperadoras();
                this.getUnidades(this.operadora);
                if(this.unidade != '')
                    this.getGrupos(this.unidade);
            }
        });
        
    </script>
@endsection

@endsection
