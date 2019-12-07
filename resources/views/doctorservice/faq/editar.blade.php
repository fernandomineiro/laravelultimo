@extends('layouts.app')

@section('content')

<div class="card" id="usuario">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('faq.listar') }}">F.A.Q</a></li>
                <li class="breadcrumb-item active" aria-current="page"><strong>Edição</strong></li>
            </ol>
        </nav>
        <div id="registros">
            <form method="POST" action="{{route('faq.atualizar', $faq->id)}}">
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
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Visibilidade:</label>
                    <div class="col-sm-10">
                        <select name="visibilidade" id="visibilidade" class="form-control">
                            <option value="">Geral</option>
                            @foreach ($modulos as $modulo)
                                <option value="{{$modulo->id}}" {{$faq->visibilidade == $modulo->id ? 'selected' : ''}}>{{$modulo->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Pergunta:</label>
                    <div class="col-md-10">
                    <textarea name="pergunta" id="pergunta" cols="30" rows="3" class="form-control">{{$faq->questao}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Resposta:</label>
                    <div class="col-md-10">
                        <textarea name="resposta" id="resposta" cols="30" rows="5" class="form-control">{{$faq->resposta}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status:</label>
                    <div class="col-sm-2">
                        <select name="status" class="form-control form-control-md">
                            <option value="A" {{($faq->ativo == 'A') ? 'selected' : ''}}>Ativo</option>
                            <option value="I" {{($faq->ativo == 'I') ? 'selected' : ''}}>Inativo</option>
                        </select>
                    </div>
                </div>                
                <div class="btn-cadastro">
                    <input type="submit" class="btn btn-primary" name="salvar" value="Salvar">
                    <input type="submit" class="btn btn-default" name="remover" value="Remover">
                    <a href="{{ route('faq.listar') }}" class="btn btn-default" name="cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection