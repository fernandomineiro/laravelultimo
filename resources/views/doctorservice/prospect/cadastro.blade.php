@extends('layouts.app')

@section('content')

<header>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
</header>
    
<div class="card" id="app">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{ route('prospect.listar') }}">Prospect</a></li>
              <li class="breadcrumb-item active" aria-current="page"><strong>Cadastro</strong></li>
            </ol>
        </nav>
        <div id="registros">
            <form method="POST" action="{{route('prospect.cadastrar')}}">
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
                    <label class="col-sm-2 col-form-label">Nome:</label>
                    <div class="col-sm-10">
                        <input type="text" name="nome" class="form-control" value="{{old('nome')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Apelido:</label>
                    <div class="col-md-10">
                        <input type="text" name="apelido" class="form-control" value="{{old('apelido')}}">
                        <p>Como gosta de ser chamado.</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">E-mail:</label>
                    <div class="col-md-10">
                        <input type="email" name="email" placeholder="exemplo@email.com" class="form-control" value="{{old('email')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Telefone:</label>
                    <div class="col-md-4">
                        <input type="tel" id="telefone" name="telefone" id="telefone1" class="form-control" value="{{old('telefone')}}">
                    </div>
                    <label class="col-sm-2 col-form-label">Ramal:</label>
                    <div class="col-md-4">
                        <input type="text" id="ramal1" name="ramal1" class="form-control" value="{{old('ramal1')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Celular:</label>
                    <div class="col-md-4">
                        <input type="tel" id="celular" name="celular" class="form-control" value="{{old('celular')}}">
                    </div>
                    <label class="col-sm-2 col-form-label">Ramal:</label>
                    <div class="col-md-4">
                        <input type="text" id="ramal2" name="ramal2" class="form-control" value="{{old('ramal2')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Descrição:</label>
                    <div class="col-sm-10">
                        <textarea name="descricao" rows="2" class="form-control rounded-0">{{old('descricao')}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Status:</label>
                    <div class="col-md-10">
                       <select name="status" class="form-control">
                            @foreach ($status as $s)
                                <option value="{{$s->id}}" {{($s->nome == "Prospect")?'selected':null}}>{{$s->nome}}</option>
                            @endforeach
                       </select>
                    </div>
                </div>                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Ativo:</label>
                    <div class="col-sm-2">
                        <select name="ativo" class="form-control form-control-md">
                            <option value="A" selected>Ativo</option>
                            <option value="I">Inativo</option>
                        </select>
                    </div>
                </div>                
                <div class="form-group">
                    <div class="row" style="padding-left: 82%;">
                        <input type="submit" class="btn btn-success" name="salvar" value="Salvar">
                        <a href="{{ route('prospect.listar') }}" class="btn btn-default" name="cancelar">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')

<script>
    $('#abrirModal').on('click', function(e) {
        e.preventDefault();
        $('.modal-container').html('Carregando...');
        $('.modal').show();

        var link = $(this).attr('href');

        $.ajax({
            url: link,
            type: 'GET',
            success: function(html) {
                $('.modal-container').html(html);
                $('#close').on('click', function(e) {
                    e.preventDefault()
                    $('.modal').css('display', 'none');
                });
            }
        });
    })

    $(function (){

        $('#telefone').mask('(00) 0000-0000');
        $('#celular').mask('(00) 00000-0000');
        $('#ramal1').mask('0000');
        $('#ramal2').mask('0000');

        var id = $('#idprospect').val();
        if(id != ''){
            $('.comunicacao').css('display', 'block')
        } else {
            $('.comunicacao').css('display', 'none')
        }

    });
    
</script>

@endsection

@endsection