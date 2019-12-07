@extends('layouts.app')

@section('content')
<div class="card" id="app"> 
    <div class="card-body">
<div id="operadora">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('parametro.listar') }}">Parametro</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><strong>Cadastrar</strong></li>
                </ol>
            </nav>
    <form method="POST" enctype="multipart/form-data" action="/salvar" >
  
        @csrf
        <h5 class="card-header">Formulário</h5>
        <div class="col-md-10 offset-md-1">
        <label for="perfil">Operadora</label>
        <select class="form-control" name="operadora" id="perfil">
                            @foreach($operadora as $operadoraa)
                                <option value="{{$operadoraa->id}}">{{$operadoraa->email}}</option>
                            @endforeach 
                    </select>
                    
                    </div>
                    <div class="col-md-10 offset-md-1">
                    <label for="perfil">Unidade</label>
                    <select class="form-control" name="operadora_unidade" id="perfil">
                            @foreach($operadoraunidade as $operadoraunidadee)
                                <option value="{{$operadoraunidadee->id}}">{{$operadoraunidadee->nome}}</option>
                            @endforeach 
                    </select>
                    </div>
                    
                    <h5 class="card-header">Prazos</h5>
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="form-group">
                    <label for="nome_fantasia">Confirmacao</label>
                    <input type="number" class="form-control" id="nome_fantasia" name="confirmacao" >
                    (dias)
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="form-group">
                    <label for="nome_fantasia">Troca</label>
                    <input type="number" class="form-control" id="nome_fantasia" name="troca" ">
                    (dias)
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="form-group">
                    <label for="nome_fantasia">Cancelamento</label>
                    <input type="number" class="form-control" id="nome_fantasia" name="cancelamento" ">
                    (dias)
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="form-group">
                    <label for="nome_fantasia">Disputa</label>
                    <input type="number" class="form-control" id="nome_fantasia" name="disputa">
                    (dias)
                </div>
            </div>
        </div>
        <h5 class="card-header">Raio</h5>
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="form-group">
                    <label for="nome_fantasia">Checkpoint</label>
                    <input type="number" class="form-control" id="nome_fantasia" name="chekpoint" >
                    (dias)
                </div>
            </div>
        </div>
      
        

        <div class="d-flex justify-content-end">
            <a href="{{route('operadora.listar')}}" class="btn btn-secondary" data-dismiss="modal">Cancelar</a>
            <input type="submit" class="btn btn-primary" style="margin-left:10px;" value="Salvar">
        </div>
        </div>
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
@endsection