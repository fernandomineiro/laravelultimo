<div class="card" id="app">
    <div class="card-body">
        <div id="registros">
            <h5>Valores</h5>
            <form action="{{route('tabela-valor.valores')}}" method="POST">
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>	
                        <strong>{{ $message }}</strong>
                </div>
                @endif
                @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>	
                        <strong>{{ $message }}</strong>
                </div>
                @endif
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="convenio">Convênio</label>
                            <select class="form-control filter" name="convenio" id="convenio">
                                <option value="">Selecione</option>
                                @foreach ($convenios as $convenio)
                                    <option value="{{$convenio->id}}">{{$convenio->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="especialidade">Especialidade</label>
                            <select class="form-control filter" name="especialidade" id="especialidade">
                                <option value="">Todas as especialidades</option>
                                @foreach ($especialidades as $especialidade)
                                    <option value="{{$especialidade->id}}">{{$especialidade->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="valor_rpa">$ RPA</label>
                            <input name="valor_rpa" type="text" class="input-valores form-control" id="valor_rpa" maxlength="6">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="valor_pj">$ CLT</label>
                            <input name="valor_clt" type="text" class="input-valores form-control" id="valor_clt" maxlength="6">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="valor_pj">$ PJ</label>
                            <input name="valor_pj" type="text" class="input-valores form-control" id="valor_pj" maxlength="6">
                        </div>
                    </div>
                    <input type="hidden" name="idtabela_valor" value="{{$tabela->id}}">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Ações Múltiplas</label>
                            <div class="form-group">
                                <button type="submit" name="acao" value="adicionar" id="cadastro" class="btn btn-default"><i class="fa fa-plus"></i></button>
                                <button type="button" id="cadastro" class="btn btn-default alterar" data-toggle="modal" data-target="#modal-alterar-valor"><i class="fa fa-pencil"></i></button>
                                <button type="submit" name="acao" value="remover" id="cadastro" class="btn btn-default"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Alterar -->
                <div class="modal fade" id="modal-alterar-valor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-alterar-valor">Aviso</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary" name="acao" value="alterar">Alterar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim Modal -->
            </form>

            <table class="table table-striped table-hover">
                <thead class="tbl-cabecalho">
                    <tr>
                        <th scope="col"><strong>Convênio</strong></th>
                        <th scope="col" style="width: 60%;"><strong>Especialidade</strong></th>
                        <th scope="col"><strong>RPA</strong></th>
                        <th scope="col"><strong>CLT</strong></th>
                        <th scope="col"><strong>PJ</strong></th>
                    </tr>
                </thead>         
                <tbody>
                    @foreach ($valores as $valor)
                    <tr class="dados">
                            <td class="convenio" data-convenio="{{$valor->convenio}}">{{$valor->convenio}}</td>
                            <td class="especialidade" data-especialidade="{{$valor->especialidade}}">{{$valor->especialidade}}</td>
                            <td>
                                {{str_replace(".", ",", $valor->valor_rpa)}}
                            </td>
                            <td>
                                {{str_replace(".", ",", $valor->valor_clt)}}
                            </td>
                            <td>
                                {{str_replace(".", ",", $valor->valor_pj)}}
                            </td>
                        </tr>                        
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div id="registros">
            <h5>Clonar Tabela</h5>
            <form action="{{route('tabela-valor.clonar', $tabela->id)}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <p>Este procedimento irá criar uma nova tabela baseada nesta. <br>Ao término do procedimento, você será redirtecionado para a nova tabela.</p>
                        </div>
                        <div class="form-group" style="float: right;">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-confirm-clonar">Clonar</button>
                        </div>
                    </div>
                </div>
                <!-- Modal Clonar -->
                <div class="modal fade" id="modal-confirm-clonar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-confirm-clonar">Confirmação</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Você confirma o processo de clonagem da tabela "{{ $tabela->nome }}"?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                <button type="submit" class="btn btn-primary" name="acao" value="clonar">Sim</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim Modal -->
            </form>
        </div>
    </div>
</div>
<script>
    const $x = jQuery.noConflict();
    $x(function(){
        
        $x('.alterar').on('click', function(request){
            var idConvenio = $x('#convenio option:selected').val();
            var idEspecialidade = $x('#especialidade option:selected').val();
            var idTabela = $x('[name=idtabela_valor]').val();
            $x.ajax({
                url: "{{ route('tabela-valor.buscar-valor') }}",
                type: "GET",
                dataType: "json",
                data: {
                    convenio: idConvenio,
                    especialidade: idEspecialidade,
                    idtabela: idTabela
                },
                success: function(data){ 
                    $x.each(data, function(index){
                        let valor_rpa = (data[index].valor_rpa == null) ? 0 : data[index].valor_rpa.replace('.', ',');
                        let valor_clt = (data[index].valor_clt == null) ? 0 : data[index].valor_clt.replace('.', ',');
                        let valor_pj = (data[index].valor_pj == null) ? 0 : data[index].valor_pj.replace('.', ',');
                        let valorRpaParaAlterar = $x('#valor_rpa').val();
                        let valorCltParaAlterar = $x('#valor_clt').val();
                        let valorPjParaAlterar = $x('#valor_pj').val();
                        $x('.modal-body').html(
                        "<div class='row'>" +
                            "<div class='col-md-6'>" +
                                "<div class='form-group'>" +
                                    '<h4>VALORES SALVOS: </h4>'+ 
                                    '<p><b>Convênio: </b>' + data[index].convenio + '</p>' +
                                    '<p><b>Especialidade: </b>' + data[index].especialidade + '</p>' +
                                    '<p><b>Valor RPA: </b>R$ ' + valor_rpa + '</p>' + 
                                    '<p><b>Valor CLT: </b>R$ ' + valor_clt + '</p>' + 
                                    '<p><b>Valor PJ: </b>R$ ' + valor_pj + '</p>' +
                                '</div>' +    
                            '</div>' +
                            "<div class='col-md-6'>" +
                                "<div class='form-group'>" +
                                    '<h4>VALORES À ALTERAR: </h4>'+ 
                                    '<p><b>Convênio: </b>' + data[index].convenio + '</p>' +
                                    '<p><b>Especialidade: </b>' + data[index].especialidade + '</p>' +
                                    '<p><b>Valor RPA: </b>R$ ' + valorRpaParaAlterar + '</p>' + 
                                    '<p><b>Valor CLT: </b>R$ ' + valorCltParaAlterar + '</p>' + 
                                    '<p><b>Valor PJ: </b>R$ ' + valorPjParaAlterar + '</p>' +
                                '</div>' +
                            '</div>' +
                        '</div>');
                    })
                },
                error: function(error) {
                    console.log(error);
                }
            });
        })

        function maskMoney(element){
            element.mask("#.##0,00", {reverse: true});
        }

        maskMoney($x('.input-valores'));

        if($x('.dados').length == 0){
            $x('[value=adicionar]').attr('disabled', false);
            $x('[value=remover]').attr('disabled', true);
            $x('.alterar').attr('disabled', true);
        } else {
            $x('[value=adicionar]').attr('disabled', true);
        }

        function filtrarGrid(idConvenio, idEspecialidade){
            var idTabela = $x('[name=idtabela_valor]').val();
            $x.ajax({
                url: "{{ route('tabela-valor.buscar-valor') }}",
                dataType: 'json',
                data: {
                    convenio: idConvenio,
                    especialidade: idEspecialidade,
                    idtabela: idTabela
                },
                success: function(data){
                    if(data == '' && idConvenio != '' && idEspecialidade != '' ){
                        $x('[value=adicionar]').attr('disabled', false);
                        $x('[value=remover]').attr('disabled', true);
                        $x('.alterar').attr('disabled', true);
                    } else {
                        $x('[value=adicionar]').attr('disabled', true);
                        $x('[value=remover]').attr('disabled', false);
                        $x('.alterar').attr('disabled', false); 
                    }
                }
            });
        }

        $x('.filter').change(function(){

            filter_function();
        
            //calling filter function each select box value change
        
        });

        $x('.dados').show(); //intially all rows will be shown

        function filter_function(){
            $x('.dados').hide(); //hide all rows
        
            var convenioFlag = 0;
            var convenioNome = $x('#convenio option:selected').text();
            var convenioId = $x('#convenio option:selected').val();
            
            var especialidadeFlag = 0;
            var especialidadeNome = $x('#especialidade option:selected').text();
            var especialidadeId = $x('#especialidade option:selected').val();
            
            //setting intial values and flags needed
            filtrarGrid(convenioId, especialidadeId);

            $x('.dados').each(function() {  
        
                if(convenioNome == 'Selecione'){   //if no value then display row
                    convenioFlag = 1;
                }
                else if(convenioNome == $x(this).find('td.convenio').data('convenio')){ 
                    convenioFlag = 1;       //if value is same display row                    
                }
                else{
                    convenioFlag = 0;
                }
            
            
                if (especialidadeNome == 'Todas as especialidades'){
                    especialidadeFlag = 1;
                }
                else if (especialidadeNome == $x(this).find('td.especialidade').data('especialidade')){
                    especialidadeFlag = 1;
                }
                else{
                    especialidadeFlag = 0;
                }

                if (convenioFlag && especialidadeFlag){
                    $x(this).show();
                }

            });
        }
    });
</script>