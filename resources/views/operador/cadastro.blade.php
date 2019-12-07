@extends('layouts.app')

@section('content')
    <div class="card" id="app">
        <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('operador.listar') }}">Operador</a></li>
                    @if($operador->id)
                        <li class="breadcrumb-item active" aria-current="page"><strong>Alterar</strong></li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page"><strong>Cadastro</strong></li>
                    @endif
                </ol>
            </nav>
            <div class="row">
                <div class="col-12">
                    <div class="tab-content" id="nav-tabContent">
                        
                        <div class="tab-pane fade show active" id="nav-operador" role="tabpanel" aria-labelledby="nav-home-tab">
                            @include('operador.operador')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
