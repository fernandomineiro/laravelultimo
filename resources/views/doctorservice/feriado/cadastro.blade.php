@extends('layouts.app')

@section('content')
    
<div class="card" id="app">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{ route('feriado.listar') }}">Feriado</a></li>
              <li class="breadcrumb-item active" aria-current="page"><strong>Cadastro</strong></li>
            </ol>
        </nav>
        <div id="registros">
            <form method="POST" action="{{route('feriado.cadastrar')}}">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">UF:</label>
                    <div class="col-sm-4">
                        <select name="uf" class="form-control">
                            <option value="">Selecione</option>
                            @foreach ($estados as $estado)
                                <option value="{{$estado->id}}">{{$estado->estado}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nome:</label>
                    <div class="col-sm-10">
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{old('nome')}}">
                    </div>
                </div>
                @error('nome')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Dia:</label>
                    <div class="col-sm-3">
                        <select name="dia" class="form-control form-control-md">
                            <option value="">-</option>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Mês:</label>
                    <div class="col-sm-3">
                        <select name="mes" class="form-control form-control-md">
                            @foreach ($meses as $chave => $valor)
                                <option value="{{$chave}}">{{$valor}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Ano:</label>
                    <div class="col-md-3">
                        <select name="ano" class="form-control form-control-md">
                            <option value="">-</option>
                            @foreach (range(date('Y')+31, date('Y')-30) as $ano)
                                <option value="{{$ano}}">{{$ano}}</option>                                
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Dia da Semana:</label>
                    <div class="col-sm-3">
                        <select name="dia_semana" class="form-control form-control-md">
                            @foreach ($dias_semana as $chave=>$valor)
                                <option value="{{$chave}}">{{$valor}}</option>
                            @endforeach                 
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Núm. Semana:</label>
                    <div class="col-sm-3">
                        <select name="num_semana" class="form-control form-control-md">
                            @foreach ($num_semana as $chave=>$valor)
                                <option value="{{$chave}}">{{$valor}}</option>
                            @endforeach               
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status:</label>
                    <div class="col-sm-3">
                        <select name="status" class="form-control form-control-md">
                            <option value="A">Ativo</option>
                            <option value="I" selected>Inativo</option>
                        </select>
                    </div>
                </div>                
                <div class="btn-cadastro">
                    <input type="submit" class="btn btn-success" name="salvar" value="Salvar">
                    <a href="{{ route('feriado.listar') }}" class="btn btn-default" name="cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection