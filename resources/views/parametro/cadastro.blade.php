@extends('layouts.app')

@section('content')
    <div class="card" id="app">
        <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('parametro.listar') }}">Parametro</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><strong>Edição</strong></li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-12">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-operadora" role="tab" aria-controls="nav-home" aria-selected="true">Parametro</a>
                            
                        </div>
                    </nav>
                    <br>
                    <div class="tab-content" id="nav-tabContent">
                        
                        <div class="tab-pane fade show active" id="nav-operadora" role="tabpanel" aria-labelledby="nav-home-tab">
                            @include('parametro.parametro')
                        </div>
                        <div class="tab-pane fade" id="nav-unidade" role="tabpanel" aria-labelledby="nav-unidade-tab">
                            @if($parametro->id != '')
                                @include('parametro.listar', ['bread' => false])
                            @endif
                        </div>
                        <div class="tab-pane fade" id="nav-operador" role="tabpanel" aria-labelledby="nav-operador-tab">
                            @if($parametro->id != '')
                                @include('parametro.listar', ['bread' => false])
                            @endif
                        </div>
                        <div class="tab-pane fade" id="nav-medico" role="tabpanel" aria-labelledby="nav-contact-tab">...</div>
                        <div class="tab-pane fade" id="nav-vagas" role="tabpanel" aria-labelledby="nav-vagas-tab">
                            @include('parametro.cadastro')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
