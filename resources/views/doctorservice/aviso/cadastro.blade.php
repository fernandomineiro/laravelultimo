@extends('layouts.app')

@section('content')
    
<div class="card" id="app">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{ route('aviso.listar') }}">Aviso</a></li>
              <li class="breadcrumb-item active" aria-current="page"><strong>Cadastro</strong></li>
            </ol>
        </nav>
        <div id="registros">
            <form method="POST" action="{{route('aviso.cadastrar')}}">
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
                        <select name="modulo" id="modulo" class="form-control" v-model="modulo" v-on:change="getOperadoras()">
                            <option value="">Geral</option>
                            @foreach ($modulos as $modulo)
                                <option value="{{$modulo->id}}">{{$modulo->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Operadora:</label>
                    <div class="col-md-10">
                        <select name="operadora" id="operadora" class="form-control" v-model="operadora" :disabled="modulo == ''" v-on:change="getUnidades(operadora)">
                            <option value="">Selecione</option>
                            <option v-for="op in operadoras" :value="op.id">@{{ op.nome_fantasia }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Unidade:</label>
                    <div class="col-sm-10">
                        <select name="unidade" id="unidade" class="form-control" v-model="unidade" :disabled="modulo == ''" v-on:change="getGrupos(unidade)">
                            <option value="">Selecione</option>
                            <option v-for="uni in unidades" :value="uni.id">@{{uni.nome}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Grupo Médico:</label>
                    <div class="col-sm-10">
                        <select name="grupo_medico" id="grupo_medico" class="form-control" v-model="grupo" :disabled="modulo == ''">
                            <option value="">Selecione</option>
                            <option v-for="grupo in grupos" :value="grupo.id">@{{grupo.nome}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Mensagem:</label>
                    <div class="col-sm-10">
                        <textarea name="mensagem" placeholder="Sua mensagem..." rows="3" class="form-control rounded-0">{{old('mensagem')}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">URL:</label>
                    <div class="col-md-10">
                        <input type="text" name="url" placeholder="http://www.exemplo.com" class="form-control" value="{{old('url')}}">
                        <p>Use http://</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="visivel" class="col-md-2 col-form-label">Visivel:</label>
                    <div style="margin: 20px 15px;">
                        <input type="checkbox" name="visivel" {{old('visivel') == 'on' ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Data:</label>
                    <div class="col-md-4 input-group date" id="data_hora_abertura" data-target-input="nearest">
                        <input name="data_hora_abertura" value="{{old('data_hora_abertura')}}" type="text" class="form-control datetimepicker-input" data-target="#data_hora_abertura">
                        <div class="input-group-append" data-target="#data_hora_abertura" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Visível Até</label>
                    <div class="col-md-4 input-group date" id="data_hora_encerramento" data-target-input="nearest">
                        <input name="data_hora_encerramento" value="{{old('data_hora_encerramento')}}" type="text" class="form-control datetimepicker-input" data-target="#data_hora_encerramento">
                        <div class="input-group-append" data-target="#data_hora_encerramento" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status:</label>
                    <div class="col-sm-2">
                        <select name="status" class="form-control form-control-md">
                            <option value="A" selected>Ativo</option>
                            <option value="I">Inativo</option>
                        </select>
                    </div>
                </div>                
                <div class="btn-cadastro">
                    <input type="submit" class="btn btn-primary" name="salvar" value="Salvar">
                    <a href="{{ route('aviso.listar') }}" class="btn btn-default" name="cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        new Vue({
            el: '#app',
            data: {
                modulo: "{{old('modulo')}}",
                operadoras: '',
                operadora: "{{old('operadora')}}",
                unidades: '',
                unidade: "{{old('unidade')}}",
                grupos: '',
                grupo: "{{old('grupo')}}"
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
                    })
                },
                getGrupos(unidade){
                    this.$http.get("{{route('aviso.grupo', ['id' => ''])}}/" + this.unidade).then(function(res) {
                        this.grupos = res.data;
                    })
                }
            }
        });
    </script>
    <script>
        $(function() { 
            $('#data_hora_abertura').datetimepicker({
                locale: 'pt-br'
            });
            $('#data_hora_encerramento').datetimepicker({
                locale: 'pt-br'
            });
        });
        /*function selecionarModulos(){
            var idModulo = $('#modulo').val();
            if (idModulo != "") {
                resetSelects()
                getOperadoras();
            }else{
                resetSelects();
            }
        }
        function resetSelects() {
            $('#operadora').html('<option value="">Selecione</option>');
            $('#unidade').html('<option value="">Selecione</option>');
            $('#grupo_medico').html('<option value="">Selecione</option>');
        }

        function selecionarOperadoras(){
            var idOperadora = $('#operadora').val();
            if (idOperadora) {
                $('#unidade').html('<option value="">Selecione</option>');
                $('#grupo_medico').html('<option value="">Selecione</option>');
                getUnidades(idOperadora);
                getGrupoMedico(idOperadora);
            }
        }

        function getOperadoras() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var operadoras = JSON.parse(this.responseText);
                    var select = document.getElementById("operadora");
                    operadoras.forEach(function(operadora) {
                        var option = document.createElement('option');
                        option.setAttribute('value', operadora['id']);
                        option.innerText = operadora['nome_fantasia'];
                        select.append(option);
                    });
                }
            };
            xhttp.open("GET", "{{route('aviso.operadoras')}}", true);
            xhttp.send();
        }

        function getUnidades(id) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var unidades = JSON.parse(this.responseText);
                    if($.isEmptyObject(unidades)){
                        $('#unidade').html('<option value="">Selecione</option>');
                    }else{
                        var select = document.getElementById("unidade");
                        unidades.forEach(function(unidade) {
                            var option = document.createElement('option');
                            option.setAttribute('value', unidade['id']);
                            option.innerText = unidade['nome'];
                            select.append(option);
                        });
                    }
                }
            };
            xhttp.open("GET", "{{route('aviso.unidade', ['id' => ''])}}/"+id, true);
            xhttp.send();
        }

        function getGrupoMedico(id){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var gruposMedicos = JSON.parse(this.responseText);
                    if($.isEmptyObject(gruposMedicos)){
                        $('#grupo_medico').html('<option value="">Selecione</option>');
                    }else{
                        var select = document.getElementById("grupo_medico");
                        gruposMedicos.forEach(function(grupoMedico) {
                            var option = document.createElement('option');
                            option.setAttribute('value', grupoMedico['id']);
                            option.innerText = grupoMedico['nome'];
                            select.append(option);
                        });
                    }
                }
            };
            xhttp.open("GET", "{{route('aviso.grupo', ['id' => ''])}}/"+id, true);
            xhttp.send();
        }*/
        
    </script>
@endsection

@endsection
