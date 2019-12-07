@extends('layouts.app')

@section('content')
    
<div class="card" id="usuario">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('faq.listar') }}">F.A.Q.</a></li>
                <li class="breadcrumb-item active" aria-current="page"><strong>Cadastro</strong></li>
            </ol>
        </nav>
        <div id="registros">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{route('faq.cadastrar')}}">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Visibilidade:</label>
                    <div class="col-sm-10">
                        <select name="visibilidade" id="visibilidade" class="form-control">
                            <option value="">Geral</option>
                            @foreach ($modulos as $modulo)
                                <option value="{{$modulo->id}}">{{$modulo->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Pergunta:</label>
                    <div class="col-md-10">
                        <textarea name="pergunta" id="pergunta" cols="30" rows="3" class="form-control" maxlength="400">{{old('pergunta')}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Resposta:</label>
                    <div class="col-md-10">
                        <textarea name="resposta" id="resposta" maxlength="1900" cols="30" rows="5" class="form-control">{{old('resposta')}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status:</label>
                    <div class="col-md-10">
                        <select name="status" class="form-control form-control-md">
                            <option value="A" selected>Ativo</option>
                            <option value="I">Inativo</option>
                        </select>
                    </div>
                </div>                
                <div class="btn-cadastro">
                    <input type="submit" class="btn btn-primary" name="salvar" value="Salvar">
                    <a href="{{ route('faq.listar') }}" class="btn btn-default" name="cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection