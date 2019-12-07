@extends('layouts.app')

@section('content')

<div class="card" id="app">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('especialidade.listar') }}">Especialidade</a></li>
                <li class="breadcrumb-item active" aria-current="page"><strong>Edição</strong></li>
            </ol>
        </nav>
        <div id="registros">
            <form method="POST" action="{{ route('especialidade.atualizar', $especialidade->id) }}">
                @method('PUT')
                @csrf
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nome:</label>
                    <div class="col-sm-10">
                        <input type="text" name="nome" class="form-control" value="{{$especialidade->nome}}">
                    </div>
                </div>
                @error('nome')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Descrição:</label>
                    <div class="col-sm-10">
                        <textarea name="descricao" rows="4" class="form-control rounded-0">{{$especialidade->descricao}}</textarea>
                    </div>
                </div>
                @error('descricao')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status:</label>
                    <div class="col-sm-10">
                        <select name="status" class="form-control" value="{{$especialidade->ativo}}">
                            <option value="A" {{($especialidade->ativo == 'A') ? 'selected' : ''}}>Ativo</option>
                            <option value="I" {{($especialidade->ativo == 'I') ? 'selected' : ''}}>Inativo</option>
                        </select>
                    </div>
                </div>
                <div style="float: right;">
                    <input type="submit" class="btn btn-success" name="salvar" value="Salvar">
                    <input type="submit" class="btn btn-default" name="remover" value="Remover">
                    <a href="{{ route('especialidade.listar') }}" class="btn btn-default" name="cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection