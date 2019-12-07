@extends('layouts.app')

@section('content')
    <div class="card" id="app">
        <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('perfil.listar') }}">Perfil</a></li>
                    @if (isCadastro())                        
                        <li class="breadcrumb-item active" aria-current="page"><strong>Cadastro</strong></li>
                    @else    
                        <li class="breadcrumb-item active" aria-current="page"><strong>Edição</strong></li>
                    @endif
                </ol>
            </nav>
            <div class="row">
                <div class="col-12">
                    @include('doctorservice.perfil.perfil')
                </div>
            </div>
        </div>
    </div>
@endsection
