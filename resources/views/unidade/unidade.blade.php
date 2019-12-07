<header>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
</header>

<div id="unidade">
    <form method="POST" id="form-unidade" enctype="multipart/form-data" {{isset($unidade->id) ? 'action='.route('unidade.alterar', ['id' => $unidade->id]).'' : ''}}>
    
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
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" maxlength="60" class="form-control" id="nome" name="nome" value="{{isset($unidade->nome) ? $unidade->nome : old('nome')}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" maxlength="19" class="form-control" id="telefone" name="telefone" value="{{isset($unidade->telefone) ? $unidade->telefone : old('telefone')}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label for="cidade">CEP</label>
                <input type="text" class="form-control" id="cep" v-model="cep" name="cep" value="{{isset($unidade->cep) ? $unidade->cep : old('cep')}}" maxlength="8">
            </div>
            <div class="col-md-1">
                <button class="btn btn-default mg-top-34" v-on:click="buscarCep" data-toggle="tooltip" title="Consultar CEP">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <input type="text" class="form-control" id="endereco" v-model="endereco" name="endereco" value="{{isset($unidade->endereco) ? $unidade->endereco : old('endereco')}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="bairro">Bairro</label>
                    {{-- <input type="text" name="bairro" class="form-control" v-model="bairro"> --}}
                    <select name="bairro" class="form-control" id="bairro" v-bind:readonly="!isDisabledBairro" v-model="bairro">
                        <option v-for="b in bairros" :value="b.id">@{{b.bairro}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cidade">Cidade</label>
                    <select class="form-control" name="cidade" id="cidade" v-bind:readonly="!isDisabledCidade" v-model="cidade">
                        <option v-for="c in cidades" :value="c.id">@{{c.cidade}}</option>
                    </select>
                </div>
            </div>
            {{-- <div class="col-md-1">
                <div class="form-group">
                    <button class="btn btn-default mg-top-34" v-bind:disabled="!isDisabledCidade" v-on:click="adicionarCidade">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                </div>
            </div> --}}
            <div class="col-md-4">
                <div class="form-group">
                    <label for="uf">UF</label>
                    <select class="form-control" name="uf" id="uf" v-model="uf" v-bind:readonly="!isDisabledUf">
                        <option v-for="estado in ufs" :value="estado.id">@{{estado.estado}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="pais">Pais</label>
                    <select class="form-control" name="pais" id="pais" v-model="pais">
                        @foreach($paises as $pais)
                            <option value="{{$pais->id}}">{{$pais->pais}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="latitude">Latitude</label>
                    <input type="text" maxlength="10" class="form-control" id="latitude" name="latitude" value="{{isset($unidade->latitude) ? $unidade->latitude : old('latitude')}}" {{-- {{--oninput="this.value = this.value.replace(/[^0-9.,]/g, '').replace(/(\..*)\./g, '$1')}}--;"--}} >
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="longitude">Longitude</label>
                    <input type="text" maxlength="10" class="form-control" id="longitude" name="longitude" value="{{isset($unidade->longitude) ? $unidade->longitude : old('longitude')}}" {{-- oninput="this.value = this.value.replace(/[^0-9.,]/g, '').replace(/(\..*)\./g, '$1');"--}}>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" class="form-control">
                        <option value="A" {{$unidade->status == 'A' ? 'selected' : ''}}>Ativo</option>
                        <option value="I" {{$unidade->status == 'I' ? 'selected' : ''}}>Inativo</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal" href="{{ route('unidade.listar') }}">Cancelar</button> -->
            <a href="{{ route('unidade.listar') }}" class="btn btn-default" name="cancelar">Cancelar</a>
            <input id="btn_submit" type="submit" class="btn btn-primary" style="margin-left:10px;" value="salvar">
        </div>        
    </form>
</div>

@section('scripts')
<script>

$(document).ready(function(){

    $('#btn_submit').on('click',function(){
        $("#form-unidade").submit();
    });
    
    $('#telefone').mask('(00) 0000-0000');

});

var app = new Vue({
  el: '#unidade',
  data: {
    modal_input: '',
    modal:{},
    cep: "{{$unidade->cep != '' ? $unidade->cep : ''}}",
    pais: "{{$unidade->pais != '' ? $unidade->pais : ''}}",
    uf: "{{$unidade->UF != '' ? $unidade->UF : ''}}",
    ufs: {!!isset($estados) ? $estados : '[]' !!},
    cidades: {!!isset($cidades) ? $cidades : '[]' !!},
    cidade: "{{$unidade->cidade != '' ? $unidade->cidade : ''}}",
    bairro: "{{$unidade->bairro != '' ? $unidade->bairro : ''}}",
    bairros: {!! isset($bairros) ? $bairros : '[]' !!},
    endereco: "{{$unidade->Endereco != '' ? $unidade->Endereco : ''}}",
    modal:{
        'titulo': "",
        'tipo': ""
    },
    isDisabledUf: {{$unidade->UF != '' ? 'true' : 'false'}},
    isDisabledCidade: {{$unidade->cidade != '' ? 'true' : 'false'}},
    isDisabledBairro: {{$unidade->bairro != '' ? 'true' : 'false'}},
    dadosCorreios: {}
  },
  methods: {
      
    salvarModal: function(event){

        event.preventDefault();
        if(this.modal.tipo == 'a'){

            this.$http.get('{{route("imagem.adicionar")}}?legenda=' + this.legenda + "&imagem=" + this.modal_input).then(response => {
                
                var legenda = this.legenda;

                this.legenda = '';

                $('.modal').modal('hide')
            });
        }else if(this.modal.tipo == 'c'){

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
    },
      buscarCep: function (event){

        event.preventDefault();

        if(this.cep.length == 8){

            this.pais = 1;

            this.$http.get('https://viacep.com.br/ws/' + this.cep +'/json/').then(response => {

                // get body data
                this.dadosCorreios = response.body;
 
                this.endereco = this.dadosCorreios.logradouro + ' ' +  this.dadosCorreios.complemento;

                // this.bairro = this.dadosCorreios.bairro;
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
        console.log(event);
        event.preventDefault();

        $('.modal').modal('show');

        this.modal.titulo = 'Bairro';
        this.modal.tipo = 'b';
        this.modal_input = '';
      },    
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
});


</script>
@endsection
