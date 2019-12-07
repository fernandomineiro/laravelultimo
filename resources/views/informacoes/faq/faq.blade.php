@extends('layouts.app')

@section('content')

<div class="card" id="usuario">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><strong>Apresentação</strong></li>
            </ol>
        </nav>
        <div class="form-group" style="text-align: center; margin: 2rem;">
            <h2>Perguntas Frequentes</h2>
        </div>
        <div class="row">            
            @if(!empty($gerais))
                <div class="card-body" style="width: 30rem;">
                    <h2 style="margin-bottom: 30px; text-align: center;">
                        <i id="geral" class="fa fa-plus nav-icon" style="cursor: pointer;"></i>
                        Geral
                    </h2>
                    @foreach ($gerais as $geral)
                        <div id="card-geral" class="callout callout-info" style="cursor: pointer;">
                            <div class="card-header" id="{{$geral->questao}}" data-toggle="collapse" data-target="#geral{{$geral->id}}" aria-expanded="true">
                                <h4>{{$geral->questao}}</h4>
                            </div>
                            <div id="geral{{$geral->id}}" class="collapse" aria-labelledby="{{$geral->questao}}">
                                <div class="card-body">
                                    {{$geral->resposta}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            @if (!empty($perguntas))
                <div class="card-body" style="width: 30rem;">
                    <h2 style="margin-bottom: 30px; text-align: center;">
                        <i id="modulo" class="fa fa-plus nav-icon" style="cursor: pointer;"></i>
                        {{$modulo->nome}}
                    </h2>
                    @foreach ($perguntas as $pergunta)
                        <div id="card-modulo" class="callout callout-warning" style="cursor: pointer;">
                            <div class="card-header" id="{{$pergunta->questao}}" data-toggle="collapse" data-target="#faq{{$pergunta->id}}" aria-expanded="true">
                                <h4>{{$pergunta->questao}}</h4>
                            </div>
                            <div id="faq{{$pergunta->id}}" class="collapse" aria-labelledby="{{$pergunta->questao}}">
                                <div class="card-body">
                                    {{$pergunta->resposta}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
<script>
    $('#geral, #modulo').click(function() {
        if(this.id == 'geral'){

            var card = $("#card-geral > .collapse");

            if(!card.hasClass('show')){

                espandir(card, $('#geral'));

            } else {

                minimizar(card, $('#geral'));
            }
        } else if(this.id == 'modulo') {

            var card = $("#card-modulo > .collapse");

            if(!card.hasClass('show')){

                espandir(card, $('#modulo'));

            } else {
                
                minimizar(card, $('#modulo'));
            }
        }             
    });

    function espandir(element, idElement) {
        element.addClass('show');
        idElement.removeClass('fa fa-plus nav-icon');
        idElement.addClass('fa fa-minus nav-icon');
    }
    function minimizar(element, idElement) {
        element.removeClass('show');
        idElement.removeClass('fa fa-minus nav-icon');            
        idElement.addClass('fa fa-plus nav-icon');
    }
</script>

@endsection