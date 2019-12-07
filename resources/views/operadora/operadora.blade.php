<div id="operadora">
    <form method="POST" enctype="multipart/form-data" {{isset($operadora->id) ? 'action='.route('operadora.alterar', ['id' => $operadora->id]).'' : ''}}>
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="nome_fantasia">Nome Fantasia</label>
                    <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" value="{{$operadora->nome_fantasia}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="razao_social">Razao social</label>
                    <input type="text" class="form-control" id="razao_social" name="razao_social" value="{{$operadora->razao_social}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" class="form-control" id="cnpj" name="cnpj" value="{{$operadora->cnpj}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cnae">CNAE</label>
                    <input type="text" class="form-control" id="cnae" name="cnae" value="{{$operadora->cnae}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="phone" class="form-control" name="telefone" id="telefone" value="{{$operadora->contato}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{$operadora->email}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="website">Website</label>
                    <input type="text" class="form-control" name="website" id="website" value="{{$operadora->website}}">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-3">
                    <label for="cidade">CEP</label>
                    <input type="text" class="form-control" id="cep" v-model="cep" name="cep">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-default mg-top-34" v-on:click="buscarCep">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="pais">Pais</label>
                        <select class="form-control" name="pais" id="pais" v-model="pais">
                            @foreach($paises as $pais)
                                <option value="{{$pais->id}}">{{$pais->pais}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="uf">UF</label>
                        <select class="form-control" name="uf" id="uf" v-model="uf" v-bind:readonly="!isDisabledUf">
                            <option v-for="estado in ufs" :value="estado.id">@{{estado.estado}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <select class="form-control" name="cidade" id="cidade" v-bind:readonly="!isDisabledCidade" v-model="cidade">
                            <option v-for="c in cidades" :value="c.id">@{{c.cidade}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <button class="btn btn-default mg-top-34" v-bind:disabled="!isDisabledCidade" v-on:click="adicionarCidade">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="endereco">Endereco</label>
                    <input type="text" class="form-control" id="endereco" v-model="endereco" name="endereco">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="bairro">Bairro</label>
                    <select class="form-control" name="bairro" id="bairro" v-bind:readonly="!isDisabledBairro" v-model="bairro">
                        <option v-for="b in bairros" :value="b.id">@{{b.bairro}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                    <div class="form-group">
                        <button class="btn btn-default mg-top-34" v-bind:disabled="!isDisabledBairro" v-on:click="adicionarBairro">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="logotipo">Logotipo</label>
                    <img src="{{asset($operadora->logotipo)}}" width="90"></img>
                    <input type="file" class="" id="logotipo" name="logotipo">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status">Status</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch1" {{$operadora->ativo == 'A' ? 'checked' : ''}} name="ativo">
                        <label class="custom-control-label" for="customSwitch1">Ativo</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{route('operadora.listar')}}" class="btn btn-secondary" data-dismiss="modal">Cancelar</a>
            <input type="submit" class="btn btn-primary" style="margin-left:10px;" value="Salvar">
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

@section('scripts')
<script>


var app = new Vue({
  el: '#operadora',
  data: {
    cep: "{{$operadora->cep != '' ? $operadora->cep : ''}}",
    pais: "{{$operadora->idpais != '' ? $operadora->idpais : ''}}",
    uf: "{{$operadora->idpais != '' ? $operadora->idpais : ''}}",
    ufs: {!!isset($estados) ? $estados : '[]' !!},
    cidades: {!!isset($cidades) ? $cidades : '[]' !!},
    cidade: "{{$operadora->idcidade != '' ? $operadora->idcidade : ''}}",
    bairro: "{{$operadora->idbairro != '' ? $operadora->idbairro : ''}}",
    bairros: {!! isset($bairros) ? $bairros : '[]' !!},
    endereco: "{{$operadora->logradouro != '' ? $operadora->logradouro : ''}}",
    modal_input: '',
    modal:{
        'titulo': "",
        'tipo': ""
    },
    isDisabledUf: {{$operadora->idestado != '' ? 'true' : 'false'}},
    isDisabledCidade: {{$operadora->idcidade != '' ? 'true' : 'false'}},
    isDisabledBairro: {{$operadora->idbairro != '' ? 'true' : 'false'}},
    dadosCorreios: {}
  },
  methods: {
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
