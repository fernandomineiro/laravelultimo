<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aquitetura Padrao') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/lodash@4.17.11/lodash.min.js')}}" defer></script>
    <script src="{{ asset('js/adminlte.min.js') }}" defer></script>
    <script src="{{ asset('js/jquery.smartWizard.min.js') }}" defer></script>
    <script src="{{ asset('plugins/jquery-mask-plugin/jquery.mask.min.js') }}" defer></script>
    <script src="{{ asset('js/maskmoney.min.js') }}" defer></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />

    <script src="{{ asset('js/vue-resource.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

    <link rel="shortcut icon" type="image/x-icon" href="img/logo_cliente_red.ico">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <link href="{{ asset('css/modal_comunicacao.css') }}" rel="stylesheet">
    <link href="{{ asset('css/banco.css') }}" rel="stylesheet">
    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/materia.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/customizacao.css') }}" rel="stylesheet">
    <link href="{{ asset('css/smart_wizard.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />

</head>
@if (isFaq())
    <body class="sidebar-mini sidebar-open sidebar-collapse">
@else
    <body class="sidebar-mini sidebar-open">
@endif
<div id="app" class="wrapper">
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fa fa-user"></i>
                    @auth
                        {{Auth::user()->name}}
                    @endauth
                    <i class="fa fa-caret-down"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">My Info</a>
                    <div class="dropdown-divider"></div>
                    <form method="post" action="{{route('logout')}}">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown-footer">Logout</button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">

            <div class="d-flex justify-content-center">
                <img src="{{url('/')}}/img/logo_cliente.png" alt="" height="32px">
            </div>
<!--            <span class="brand-text font-weight-light">Arquitetura Padrao</span> -->
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">


                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-table"></i>
                            <p>
                                Administrativos
                                <i class="fa fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (\Auth::user()->hasAcesso("Apresentação de FAQ"))                            
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>
                                        Informações
                                    </p>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a> 
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{route('faq')}}" class="nav-link">
                                            <i class="fa fa-question-circle-o nav-icon"></i>
                                            <p>F.A.Q.</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Banco"))
                            <li class="nav-item">
                                <a href="{{route('banco.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Banco</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Aviso"))
                            <li class="nav-item">
                                <a href="{{route('aviso.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Aviso</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Especialidade"))                            
                            <li class="nav-item">
                                <a href="{{route('especialidade.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Especialidade</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Feriado"))
                            <li class="nav-item">
                                <a href="{{route('feriado.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Feriado</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Convênio"))
                            <li class="nav-item">
                                <a href="{{route('convenio.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Convênio</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Instituição"))
                            <li class="nav-item">
                                <a href="{{route('instituicao.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Instituição</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Prospect"))
                            <li class="nav-item">
                                <a href="{{route('prospect.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Prospect</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Usuário"))
                            <li class="nav-item">
                                <a href="{{route('usuario.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Usuário</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Perfil"))
                            <li class="nav-item">
                                <a href="{{route('perfil.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Perfil</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Operadora"))
                            <li class="nav-item">
                                <a href="{{route('operadora.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Operadora</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Operador"))
                            <li class="nav-item">
                                <a href="{{route('operador.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Operador</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Unidade"))
                            <li class="nav-item">
                                <a href="{{route('unidade.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Unidade</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Tabela de Valores"))
                            <li class="nav-item">
                                <a href="{{route('tabela-valor.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Tabela</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Parametro"))
                            <li class="nav-item">
                                <a href="{{route('parametro')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Parametro</p>
                                </a>
                            </li>        
                            @endif
                            @if (\Auth::user()->hasAcesso("Sala"))
                            <li class="nav-item">
                                <a href="{{route('sala.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Sala</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Vaga"))
                            <li class="nav-item">
                                <a href="{{route('vaga.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Vaga</p>
                                </a>
                            </li>
                            @endif                           
                            @if (\Auth::user()->hasAcesso("Plantão"))
                            <li class="nav-item">
                                <a href="{{route('plantao.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Plantão</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Empresa"))
                            <li class="nav-item">
                                <a href="pages/tables/data.html" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Empresa</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Blacklist"))
                            <li class="nav-item">
                                <a href="pages/tables/data.html" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Blacklist</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("FAQ"))
                            <li class="nav-item">
                                <a href="{{route('faq.listar')}}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>FAQ</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("LOG"))
                            <li class="nav-item">
                                <a href="pages/tables/data.html" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>LOG</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Banner"))
                            <li class="nav-item">
                                <a href="pages/tables/data.html" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Banner</p>
                                </a>
                            </li>
                            @endif
                            @if (\Auth::user()->hasAcesso("Página"))
                            <li class="nav-item">
                                <a href="pages/tables/data.html" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>Página</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
    <!-- /.navbar -->
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>
</div>
<div id="loading" class="loading" >Loading&#8230;</div>
@yield('scripts')
<script>
    $(document).ready(function(){

        var tam = $(window).width();
        
        if (tam <=991){

            $(".sidebar-mini").removeClass('sidebar-open');
        }

        $(".nav-treeview > .nav-item > .nav-link").click(function(){
            $("#loading").show(); 
        });

        setTimeout(() => {
            $("#loading").hide();    
        }, 500);
    });
</script>

</body>
</html>


<!-- <ul class="navbar-nav ml-auto">
