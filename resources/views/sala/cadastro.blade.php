@extends('layouts.app')

@section('content')
    
<div class="card" id="app">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{ route('sala.listar') }}">Sala</a></li>
              <li class="breadcrumb-item active" aria-current="page"><strong>Cadastro</strong></li>
            </ol>
        </nav>
        <div id="registros">
            <form method="POST" action="{{route('sala.cadastrar')}}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unidade">Unidade:</label>
                            <select name="unidade" class="form-control">
                                <option value="">Selecione</option>
                                @foreach ($unidades as $unidade)
                                    <option value="{{$unidade->id ? $unidade->id : old('unidade')}}">{{$unidade->nome}}</option>
                                @endforeach
                            </select>
                            @error('unidade')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Nome:</label>
                            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{old('nome')}}" maxlength="100">
                            @error('nome')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="descricao">Descrição:</label>
                            <textarea name="descricao" class="form-control" maxlength="400" cols="30" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="cor">Cor</label>
                            <input type="color" name="cor" class="form-control">
                        </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="especialidade">Especialidade:</label>
                            <select name="especialidade" class="form-control">
                                <option value="">Selecione</option>
                                @foreach($especialidades as $e)
                                    <option value="{{$e->id}}">{{$e->nome}}</option>
                                @endforeach
                            </select>
                            @error('especialidade')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select name="status" class="form-control">
                                <option value="A">Ativo</option>
                                <option value="I">Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>                
                <div class="btn-cadastro">
                    <input type="submit" class="btn btn-primary" name="salvar" value="Salvar">
                    <a href="{{ route('sala.listar') }}" class="btn btn-default" name="cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#registros',
        data: {
            unidade: ""
        }
    })
</script>

@endsection