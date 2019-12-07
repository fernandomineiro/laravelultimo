@extends('layouts.app')

@section('content')

<header>
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
</header>

<div class="card" id="usuario">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('usuario.listar') }}">Usuário</a></li>
                <li class="breadcrumb-item active" aria-current="page"><strong>Edição</strong></li>
            </ol>
        </nav>
        <div id="registros">
            <form method="POST" action="{{route('usuario.atualizar', $usuario->id)}}" enctype="multipart/form-data">
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
                @if (!empty($usuario->foto))
                    <div class="col-md-10" style="">
                        <figure>
                            <img src="{{asset($usuario->foto)}}" width="120" class="img-thumbnail">
                        </figure>                            
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="modulo">Módulo:</label>
                            <select name="modulo" id="modulo" v-model="modulo" 
                            v-on:change="buscarPerfis(modulo)" class="form-control" required>
                                <option value="">Selecione</option>
                                @foreach ($modulos as $modulo)
                                    <option value="{{$modulo->id}}">{{$modulo->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if ($errors->has('modulo'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('modulo') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="perfil">Perfil:</label>
                            <select name="perfil" id="perfil" v-model="perfilEscolhido" class="form-control" required>
                                <option value="">Selecione</option>
                                <option v-for="p in perfil" :value="p.id">@{{p.nome}}</option>
                            </select>
                        </div>
                    </div>
                    @if ($errors->has('perfil'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('perfil') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row" v-show="modulo == 2">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="operadora">Operadora:</label>
                            <select name="operadora" id="operadora" class="form-control" v-model="operadora" v-on:change="buscarUnidades(operadora)" :required="modulo == 2">
                                <option value="">Selecione</option>
                                @foreach ($operadoras as $operadora)
                                    <option value="{{$operadora->id}}" :checked="operadora">{{$operadora->nome_operadora}}</option>
                                @endforeach                            
                            </select>                            
                        </div>
                    </div>
                </div>
                <div class="row" v-show="operadora != ''">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="unidade">Unidade:</label>
                            <select name="unidade" id="unidade" class="form-control" v-model="unidadeEscolhida">
                                <option value="">Selecione</option>
                                <option v-for="unidade in unidades" :value="unidade.id">@{{unidade.nome}}</option>
                            </select>                            
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label id="label-cpf" for="cpf">CPF:</label>
                            <input type="text" name="cpf" id="cpf" class="form-control" value="{{$usuario->cpf}}">
                        </div>
                        @if ($errors->has('cpf'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('cpf') }}</strong>
                            </span>
                        @endif
                        <div class="col-md-8">
                            <label id="label-nome" for="nome">Nome:</label>
                             <input type="text" name="nome" id="nome" class="form-control" maxlength="75" value="{{$usuario->name}}">
                            <input type="hidden" name="idpessoa" id="idpessoa" value="{{$usuario->idpessoa}}">
                        </div>
                        @if ($errors->has('idpessoa'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('idpessoa') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="sexo">Sexo:</label>
                            <select name="sexo" id="sexo" class="form-control" required>
                                <option value="">Selecione</option>
                                <option value="M" {{($usuario->sexo == 'M') ? 'selected' : ''}}>Masculino</option>
                                <option value="F" {{($usuario->sexo == 'F') ? 'selected' : ''}}>Feminino</option>
                            </select>
                        </div>
                        @if ($errors->has('sexo'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('sexo') }}</strong>
                            </span>
                        @endif
                        <div class="col-md-4">
                            <label for="estado_civil">Estado Civil:</label>
                            <select name="estado_civil" id="estado_civil" class="form-control" required>
                                <option value="">Selecione</option>
                                @foreach ($estadoCivil as $estado)
                                    @if ($usuario->idestado_civil === $estado->id)
                                        <option value="{{$estado->id}}" selected>{{$estado->estado}}</option>
                                    @else
                                        <option value="{{$estado->id}}" >{{$estado->estado}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('estado_civil'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('estado_civil') }}</strong>
                            </span>
                        @endif
                        <div class="col-md-4">
                            <label for="data_nascimento">Data de Nascimento:</label>
                            <input name="data_nascimento" type="text" value="{{date('d/m/Y', strtotime($usuario->data_nascimento))}}" id="data_nascimento" class="form-control" required placeholder="__/__/____">
                        </div>
                        @if ($errors->has('data_nascimento'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('data_nascimento') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="apelido">Apelido:</label>
                            <input name="apelido" type="text" value="{{$usuario->apelido}}" class="form-control" maxlength="45">
                            <p>Como gosta de ser chamado.</p>
                        </div>
                        @if ($errors->has('apelido'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('apelido') }}</strong>
                            </span>
                        @endif
                        <div class="col-md-6">
                            <label for="email">E-mail:</label>
                            <input name="email" type="email" class="form-control" placeholder="dominio@email.com.br" value="{{$usuario->email}}" required maxlength="255">
                        </div>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="label-foto" for="foto" class="col-sm-2 col-form-label">Foto</label>
                            <input type="file" id="foto" class="filepond" name="foto" data-max-file-size="3MB" accept="image/*">
                            <p>
                                <ul>
                                    <li>Tamanho máximo permitido para a foto: 3MB.</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    @if ($errors->has('foto'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('foto') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="senha">Senha:</label>
                            <input type="password" required name="password" id="senha" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}">
                            <div id="senhaBarra" class="progress" style="display: none; height: 14px; background-color: white;">
                                <div id="senhaForca" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <p>A senha deve conter no mínimo 8 caracteres (letras, números)<br>
                                e com pelo menos 1 caractere especial (@, !, $, %, #).</p>
                        </div>
                    </div>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="senha">Confirmar Senha:</label>
                            <input type="password" required name="password_confirmation" id="confirmSenha" class="form-control">
                            <p>Digite novamente a sua senha.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select name="status" class="form-control form-control-md">
                                <option value="A" {{($usuario->ativo == 'A') ? 'selected' : ''}}>Ativo</option>
                                <option value="I" {{($usuario->ativo == 'I') ? 'selected' : ''}}>Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>                
                <div class="btn-cadastro">
                    <input type="submit" class="btn btn-primary" name="salvar" value="Salvar">
                    <input type="submit" class="btn btn-default" name="remover" value="Remover">
                    <a href="{{ route('usuario.listar') }}" class="btn btn-default" name="cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
    <script>

        /**
        *   Função usada para formatar a data vinda do banco de dados
        *   para o padrão brasileiro.
        */
        function formatarData(date) {
            var aux = date.split('-');        
            data = aux[2]+"/"+aux[1]+"/"+aux[0];        
            return data;
        }

        $(document).ready(function() {            
            $('#data_nascimento').mask('00/00/0000');            
            var inputCPF = $('#cpf');
            inputCPF.on('blur', function(){
                $(this).mask('000.000.000-00', {reverse: true});
            });

            /**
            *   Autocomplete em CPF, Nome, Sexo, Data de Nascimento e Estado civil:
            */
            $('#cpf').autocomplete({
                source: function(request, response){
                    $.ajax({
                        url: "{{ route('usuario.cpf') }}",
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function(data){ 
                            response(data);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                },
                minLength: 2,
                select: function( event, ui ) {
                    var data = formatarData(ui.item.data_nascimento)
                    $( "#data_nascimento" ).val( data );
                    
                    $( "#idpessoa" ).val( ui.item.idpessoa );
                    $( "#cpf" ).val( ui.item.cpf );
                    $( "#nome" ).val( ui.item.nome );
                    $( "#sexo" ).val( ui.item.sexo );
                    $( "#estado_civil" ).val( ui.item.idestado_civil );
                    
                    return false;
                }
            })
            .autocomplete('instance')._renderItem = function(ul, item) {
                return $( "<li>" )
                    .append( "<div>" + item.cpf + " - " + item.nome + "</div>" )
                    .appendTo( ul );
            }
            /**
                Fim autocomplete
            */
            
            FilePond.registerPlugin(
                FilePondPluginImagePreview,
                FilePondPluginImageExifOrientation,
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType
            );
            
            FilePond.setOptions({
                server: {
                    url: '/usuario/upload',
                    process: {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        onload: (response) => {
                            console.log(response);
                            return response;
                        }
                    }
                }
            });

            const inputElement = document.querySelector('input[type="file"]');
            const pond = FilePond.create( inputElement );

            $(function (){
                $('#senha').keyup(function (e){
                    var senha = $(this).val();        
                    if(senha == ''){
                        $('#senhaBarra').hide();
                    }else{
                        var fSenha = forcaSenha(senha);
                        var texto = "";
                        $('#senhaForca').css('width', fSenha + '%');
                        $('#senhaForca').removeClass();
                        $('#senhaForca').addClass('progress-bar');
                        if(fSenha <= 40){
                            texto = 'Fraca';
                            $('#senhaForca').addClass('progress-bar-danger');
                            $('#senhaForca').css("background-color","#D50000");
                        }
                        if(fSenha > 40 && fSenha <= 70){
                            texto = 'Razoavel';
                            $('#senhaForca').css("background-color","#FFCC80");

                        }if(fSenha > 70 && fSenha <= 90){
                            texto = 'Forte';
                            $('#senhaForca').addClass('progress-bar-success');
                            $('#senhaForca').css("background-color","#00E676");
                        }if(fSenha >= 90){
                            texto = 'Muito Forte';
                            $('#senhaForca').addClass('progress-bar-success');
                            $('#senhaForca').css("background-color","#00C853");
                        }

                        $('#senhaForca').text(texto);
                        $('#senhaBarra').show();
                    }
                });
            });
            
            function forcaSenha(senha){
                var forca = 0;
                
                var regLetrasMa     = /[A-Z]/;
                var regLetrasMi     = /[a-z]/;
                var regNumero       = /[0-9]/;
                var regEspecial     = /[!@#$%&*?]/;
                
                var tam         = false;
                var tamM        = false;
                var letrasMa    = false;
                var letrasMi    = false;
                var numero      = false;
                var especial    = false;

                if(senha.length >= 8) tam = true;
                if(senha.length >= 10) tamM = true;
                if(regLetrasMa.exec(senha)) letrasMa = true;
                if(regLetrasMi.exec(senha)) letrasMi = true;
                if(regNumero.exec(senha)) numero = true;
                if(regEspecial.exec(senha)) especial = true;
                
                if(tam) forca += 10;
                if(tamM) forca += 10;
                if(letrasMa) forca += 10;
                if(letrasMi) forca += 10;
                if(letrasMa && letrasMi) forca += 20;
                if(numero) forca += 20;
                if(especial) forca += 20;
                
                return forca;
            }
        });
    </script>
    <script>
        new Vue({
            el: '#usuario',
            data: {
                modulo: "{{$usuario->idmodulo != '' ? $usuario->idmodulo : 'Selecione'}}",
                perfil: '',
                perfilEscolhido: '{{$usuario->idperfil != '' ? $usuario->idperfil : ''}}',
                unidades: '',
                unidadeEscolhida: '{{$usuario->idoperador_unidade != '' ? $usuario->idoperador_unidade : ''}}',
                operadora: '{{$usuario->idoperadora != '' ? $usuario->idoperadora : ''}}'
            },
            methods: {
                buscarPerfis(modulo){
                    this.$http.get("{{route('usuario.perfil', ['id' => ''])}}/" + this.modulo).then(function(res) {
                        this.perfil = res.data;
                    });
                },
                buscarUnidades(operadora){
                    this.$http.get("{{route('usuario.unidades', ['id' => ''])}}/" + this.operadora).then(function(res) {
                        this.unidades = res.data;
                    })
                }
            },
            mounted: function () {
                this.buscarPerfis(this.modulo);
                this.buscarUnidades(operadora);
            }
        });
    </script>
@endsection

@endsection