<form method="POST" action="{{route('comunicacao.cadastrar')}}">
    @csrf    
    <div class="row">
        <div class="col-md-12">
            <label for="tipo">Tipo:</label>
            <select name="tipo" class="form-control @error('tipo') is-invalid @enderror">
                <option value="">Selecione</option>
                @foreach ($tipo as $t)
                    <option value="{{$t->id}}">{{$t->nome}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @error('tipo')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <div class="row">
        <div class="col-md-12" style="margin: 10px 0;">
            <label for="mensagem">Mensagem:</label>
            <textarea name="mensagem" class="form-control" rows="4"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="data" class="col-sm-2 col-form-label-md">Data:</label>
        <div class="col-md-10">
            <input type="datetime-local" name="data" class="form-control" required>
        </div>
    </div>
    <div class="form-group row">
            <label for="ativo" class="col-sm-2 col-form-label-md">Status:</label>
            <div class="col-sm-10">
                <select name="ativo" class="form-control form-control-md">
                    <option value="A" selected>Ativo</option>
                    <option value="I">Inativo</option>
                </select>
            </div>
        </div>
    <div class="form-group row" style="float: right">
        <div class="col-md-12" >
            <button type="button" data-dismiss="modal" class="btn btn-secondary">Cancelar</button>
            <button class="btn btn-success">Salvar</button>
        </div>
    </div>
</form>