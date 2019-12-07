<header>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
</header>

<div id="operador">
    <form method="POST" enctype="multipart/form-data" {{isset($operador->id) ? 'action='.route('operador.alterar', ['id' => $operador->id]).'' : ''}}>
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
        @if (!empty($operador->foto))
            <div class="col-md-10" style="">
                <figure>
                    <img src="{{asset($operador->foto)}}" width="120" class="img-thumbnail">
                </figure>                            
            </div>
        @endif
        <div class="row">    
            <div class="col-md-12">
                <div class="form-group">
                    <label for="perfil">Perfil</label>
                    <select class="form-control" name="perfil" id="perfil" v-model="perfil">
                        <option value="">Selecione</option>
                        @foreach($perfis as $perfil)
                            <option value="{{$perfil->id}}">{{$perfil->nome}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" class="form-control" maxlength="14" id="cpf" name="cpf" value="{{($operador->cpf) ? $operador->cpf : old('cpf')}}">
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" v-model="nome">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="data-nascimento">Data de Nascimento</label>
                    <input type="text" class="form-control" id="data-nascimento"  maxlength="10"  name="dataNascimento" value="{{($operador->id) ? date("d/m/Y", strtotime(str_replace('-','/',$operador->dataNascimento))) : old('dataNascimento')}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sexo">Sexo</label>
                    <select class="form-control" name="sexo" id="sexo" value="{{$operador->sexo}}" v-model="sexo">
                        <option value="">Selecione</option>
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="apelido">Apelido</label>
                    <input type="text" class="form-control" name="apelido" id="apelido" v-model="apelido">
                </div>
            </div>
            <div class="col-md-6">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" v-model="email">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="foto" style="margin-bottom: 16px; margin-top: 16px;">Foto</label>
                    <input type="file" id="foto" class="filepond" name="foto" accept="image/*">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" class="form-control{{ $errors->has('senha') ? ' is-invalid' : '' }}" name="senha" id="senha" v-model="senha" required="requerid">
                    <div id="senhaBarra" class="progress" style="display: none; height: 14px; background-color: white;">
                        <div id="senhaForca" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%; height: 0.8rem; font-size: 0.78rem;">
                        </div>
                    </div>
                    @if ($errors->has('senha'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('senha') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="senha_confirmation" >Senha Confirmação</label>
                    <input id="senha_confirmation" type="password" v-model="password" class="form-control{{ $errors->has('senha_confirmation') ? ' is-invalid' : '' }}" name="senha_confirmation" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="operadora">Operadora</label>
                    <select class="form-control" name="operadora" id="operadora" v-model="operadora" v-on:change="buscarUnidades(operadora)">
                        <option value="">Selecione</option>
                        @foreach($operadoras as $operadora)
                            <option value="{{$operadora->id}}">{{$operadora->nome}}</option>
                        @endforeach 
                    </select>   
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="unidade">Unidade</label>
                    <select class="form-control" name="unidade" v-model="unidade" id="unidade">
                        <option value="">Selecione</option>
                        <option v-for="unidade in unidades" :value="unidade.id">@{{unidade.nome}}</option>
                    </select>   
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" value="{{$operador->id ? $operador->telefone1 : old('telefone')}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ramal">Ramal</label>
                    <input type="text" class="form-control ramal" maxlength="4" name="ramal" value="{{$operador->id ? $operador->ramal1 : old('ramal')}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="telefone2">Telefone</label>
                    <input type="text" class="form-control" id="telefone2" name="telefone2" value="{{$operador->id ? $operador->telefone2 : old('telefone2')}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ramal2">Ramal</label>
                    <input type="text" class="form-control ramal" maxlength="4" name="ramal2" value="{{$operador->id ? $operador->ramal2 : old('ramal2')}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="celular">Celular</label>
                    <input type="text" class="form-control" id="celular" name="celular" value="{{$operador->id ? $operador->celular : old('celular')}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ramal3">Ramal</label>
                    <input type="text" class="form-control ramal" maxlength="4" name="ramal3" value="{{$operador->id ? $operador->ramal3 : old('ramal3')}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status">Status</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch1" {{$operador->status == 'A' ? 'checked' : ''}} name="status" Checked>
                        <label class="custom-control-label" for="customSwitch1">Ativo</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group" style="float: right;">
                    <input type="submit" class="btn btn-primary" name="salvar" value="Salvar">
                    @if ($operador->id)
                    <input type="submit" class="btn btn-default" name="remover" value="remover">
                    @endif
                    <a href="{{ route('operador.listar') }}" class="btn btn-default" name="cancelar">Cancelar</a>
                </div>
            </div>
        </div>
    </form>
    <div class="area-modal">
        <div class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@{{modal.titulo}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input class="form-control" v-model="modal_input">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" v-on:click="salvarModal">Salvar</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>

</style>
@section('scripts')
<script type="text/javascript">

$(document).ready(function(){

    $('#cpf').mask('000.000.000-00', {reverse: true});
    $('#data-nascimento').mask('00/00/0000');
    $('#telefone').mask('(00) 0000-0000');
    $('#telefone2').mask('(00) 0000-0000');
    $('#celular').mask('(00) 00000-0000');
    $('.ramal').mask('0000');
    
    function formatarData(date) {
        var aux = date.split('-');        
        data = aux[2]+"/"+aux[1]+"/"+aux[0];        
        return data;
    }

    // Autocomplete do campo CPF:
    $('#cpf').autocomplete({
        source: function(request, response){
            $.ajax({
                url: "{{ route('operador.cpf') }}",
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
        minLength: 1,
        select: function( event, ui ) {
            var data = formatarData(ui.item.data_nascimento)
            $( "#cpf" ).val( ui.item.cpf );
            $( "#nome" ).val( ui.item.nome );
            $( "#sexo" ).val( ui.item.sexo );
            $( "#data-nascimento" ).val( data );
            
            return false;
        }
    })
    .autocomplete('instance')._renderItem = function(ul, item) {
        return $( "<li>" )
            .append( "<div>" + item.cpf + " - " + item.nome + "</div>" )
            .appendTo( ul );
    }
    // Fim Autocomplete.

    // $('#operadora').select2();

    //correção css do select2 CPF
    $('.select2-selection.select2-selection--single').css('margin-top','22px');
    $('.select2-selection__arrow').css('top','23px');

    // plugin de fotos:
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginFileValidateSize
    );
    
    FilePond.setOptions({
        server: {
            url: "{{route('uploadOperador')}}",
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
    const pond = FilePond.create( inputElement);
    // Fim plugin de fotos.
    
    // Força da Senha:
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
                    texto = 'Boa';
                    $('#senhaForca').addClass('progress-bar-success');
                    $('#senhaForca').css("background-color","#00E676");
                }if(fSenha >= 90){
                    texto = 'Muito boa';
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
    // Fim Força da Senha.
    
});

var app = new Vue({
  el: '#operador',
  data: {
    perfil: "{{ $operador->idperfil != '' ? $operador->idperfil : old('perfil') }}",
    nome: "{{ $operador->nome != '' ? $operador->nome : old('nome') }}",
    sexo: "{{ $operador->sexo != '' ? $operador->sexo : old('sexo') }}",
    apelido: "{{ $operador->apelido != '' ? $operador->apelido : old('apelido') }}",
    email: "{{ $operador->email != '' ? $operador->email : old('email') }}",
    senha: "{{ old('senha') }}",
    password: "{{ old('password') }}",
    operadora: "{{ $operador->idoperadora != '' ? $operador->idoperadora : old('operadora') }}",
    unidades: [],
    unidade: "{{ $operador->idunidade != '' ? $operador->idunidade : old('unidade') }}",
    cep: "{{$operador->cep != '' ? $operador->cep : ''}}",
    pais: "{{$operador->idpais != '' ? $operador->idpais : ''}}",
    uf: "{{$operador->idpais != '' ? $operador->idpais : ''}}",
    ufs: {!!isset($estados) ? $estados : '[]' !!},
    cidades: {!!isset($cidades) ? $cidades : '[]' !!},
    cidade: "{{$operador->idcidade != '' ? $operador->idcidade : ''}}",
    bairro: "{{$operador->idbairro != '' ? $operador->idbairro : ''}}",
    bairros: {!! isset($bairros) ? $bairros : '[]' !!},
    endereco: "{{$operador->logradouro != '' ? $operador->logradouro : ''}}",
    modal_input: '',
    modal:{
        'titulo': "",
        'tipo': ""
    },
    isDisabledUf: {{$operador->idestado != '' ? 'true' : 'false'}},
    isDisabledCidade: {{$operador->idcidade != '' ? 'true' : 'false'}},
    isDisabledBairro: {{$operador->idbairro != '' ? 'true' : 'false'}},
    dadosCorreios: {}
  },
  methods: {
      buscarUnidades(operadora) {
          this.$http.get("{{route('operador.unidades', ['id' => ''])}}/" + this.operadora).then(function(response) {
            console.log(response.body);
            this.unidades = response.body;
          })
      },
      buscarCep: function (event){

        event.preventDefault();

        if(this.cep.length == 8){

            this.pais = 1;

            this.$http.get('https://viacep.com.br/ws/' + this.cep +'/json/').then(response => {

                // get body data
                this.dadosCorreios = response.body;

                this.endereco = this.dadosCorreios.logradouro + ' ' +  this.dadosCorreios.complemento;

                this.$http.get('{{route("estado.buscar")}}?uf=' + this.dadosCorreios.uf).then(response => {

                    // get body data
                    var dadosEstado = response.body;

                    this.uf = dadosEstado.uf.id;

                    this.$http.get('{{route("cidade.buscar")}}?cidade=' + this.dadosCorreios.localidade + '&uf=' + this.uf).then(response => {

                        // get body data
                        var dadosCidade = response.body;

                        this.$http.get('{{route("cidade.buscar.buscar-por-estado")}}/' + this.uf).then(response => {

                            // get body data
                            this.cidades = response.body.cidades;

                            this.isDisabledCidade = true;

                            this.cidade = dadosCidade.cidade.id;

                            this.$http.get('{{route("bairro.buscar")}}?cidade=' + dadosCidade.cidade.id + '&bairro=' + this.dadosCorreios.bairro).then(response => {

                                // get body data
                               // this.bairros = response.body.bairros;
                               var dadosBairro = response.body;

                               this.$http.get('{{route("bairro.buscar.buscar-por-cidade")}}/' + dadosCidade.cidade.id).then(response => {

                                    // get body data
                                    this.bairros = response.body.bairros;

                                    this.isDisabledBairro = true;

                                    this.bairro = dadosBairro.bairro.id;
                               });
                            });

                        });
                    });
                });

                }, response => {
                // error callback
            });
        }else{

            alert("Formato cep inválido");
        }
      },
      adicionarCidade: function(event){

        event.preventDefault();

        $('.modal').modal('show');

        this.modal.titulo = 'Cidade';
        this.modal.tipo = 'c';
        this.modal_input = '';

      },
      adicionarBairro: function(event){

        event.preventDefault();

        $('.modal').modal('show');

        this.modal.titulo = 'Bairro';
        this.modal.tipo = 'b';
        this.modal_input = '';
      },

      salvarModal: function(event){

        event.preventDefault();
        if(this.modal.tipo == 'c'){

            this.$http.get('{{route("cidade.criar")}}?uf=' + this.uf + "&cidade=" + this.modal_input).then(response => {

                var uf = this.uf;

                this.uf = '';

                $('.modal').modal('hide')
            });
        }else if(this.modal.tipo == 'b'){

            this.$http.get('{{route("bairro.criar")}}?cidade=' + this.cidade + "&bairro=" + this.modal_input).then(response => {

                var cidade = this.cidade;

                this.cidade = '';

                $('.modal').modal('hide')
            });
        }
      }
  },
  mounted: function(){
    if (this.operadora != '') {
        this.buscarUnidades(this.operadora);        
    }
  },
  watch: {
    pais: function (val) {

        if(val != ""){

            this.$http.get('{{route("estado.buscar.buscar-por-pais")}}/' + val).then(response => {

                // get body data
                this.ufs = response.body.estados;

                this.isDisabledUf = true;
            });
        }else{

            this.isDisabledUf = false;
        }
    },
    uf: function (val) {

      if(val != ""){

          this.$http.get('{{route("cidade.buscar.buscar-por-estado")}}/' + val).then(response => {

              // get body data
              this.cidades = response.body.cidades;

              this.isDisabledCidade = true;
          });
      }else{

          this.cidade = '';
          this.isDisabledCidade = false;
      }
    },
    cidade: function(val){

        if(val != ""){

            this.$http.get('{{route("bairro.buscar.buscar-por-cidade")}}/' + val).then(response => {

                // get body data
                this.bairros = response.body.bairros;

                this.isDisabledBairro = true;
            });
        }else{

          this.bairro = '';
          this.isDisabledCidade = false;
      }
    }
  }
})

</script>
@endsection
