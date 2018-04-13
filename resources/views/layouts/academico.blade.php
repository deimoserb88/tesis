<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tesis') }}</title>
	<link type="image/x-icon" href="http://www.ucol.mx/cms/img/favicon.ico" rel="icon" />
    <!-- Styles -->
    {{ Html::style('public/assets/vendor/bootstrap/dist/css/bootstrap.min.css') }}
    {{ Html::style('https://fonts.googleapis.com/css?family=Lato:100,300,400,700') }}<!-- Fonts -->
    {{-- Html::style('https://file.myfontastic.com/YkvRruhw4K6cVhm9Z6RdGC/icons.css') --}}<!-- Iconos -->
    {{ Html::style('http://www.ucol.mx/cms/headerfooterapp.css') }}	 {{-- CSS de la universidad --}}

    @yield('estilos') <!--Para agregar estilos propios de cada modulo-->

    <style>
        body {
            font-family: 'Lato';
            position: relative;
        }
        .affix {
            top:0;
            width: 100%;
            z-index: 9999 !important;
        }
        .navbar {
            margin-bottom: 10px;
        }

        .affix ~ .container-fluid {
           position: relative;
           top: 50px;
        }
        .fa-btn {
            margin-left: 6px;
        }
        .lm {
            border-radius: 45%;
        }

    </style>

</head>
<body id="app-layout"  data-spy="scroll" data-target=".navbar" data-offset="50">
<div id="estructura">
        <header id="p-header" style="margin-bottom: 0;">
        <div id="p-top">
            <div class="p-encabezado">
                <div class="linkUcol">
                    <a class="escudo" href="http://www.ucol.mx/">&nbsp;</a>
                    <a class="nombre" href="http://www.ucol.mx/">&nbsp;</a>
                </div>
            	<div class="TituloDep">Facultad de Ingeniería Electromecánica</div>
            </div><!--encabezdo-->
        </div><!--top-->
    </header>
    <nav class="navbar navbar-default"  data-spy="affix" data-offset-top="84">
        <div class="container" style="width: 100%;">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/home') }}">
                    TESIS <i class="fas fa-home"></i>
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-nav">
                    @if(Auth::user()->priv < 5)
                        <li><a href="{{ url('/tesis') }}">Tesis <i class="fas fa-file-alt"></i></a></li>
                        <li><a href="{{ url('/usuariosTesistas') }}">Tesistas <i class="fas fa-graduation-cap"></i></a></li>
                    @endif

                    @if(Auth::user()->priv <= 2 || $rol <= 4)
                        <li><a href="{{ url('/usuariosAcademicos') }}">Usuarios académicos <i class="fas fa-users"></i></a></li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    @yield('menu_items')
                    <li>
                        <a href="{{ url('/mensajes/'.Auth::user()->id.'/2') }}">
                            @php
                                $mnl = tesis\Mensaje::where('idusuario_para','=',Auth::user()->id)->where('leido','=','0')->count();
                            @endphp
                            @if($mnl>0)
                                <i class="fas fa-comment text-danger" ></i>
                                <span class="label label-danger lm">{{ $mnl }}</span>
                            @else
                                <i class="fas fa-comment text-muted" ></i>
                                <span class="label label-default lm">{{ $mnl }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ explode(" ",Auth::user()->nombre)[0]  }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/home') }}">Panel de actividades <i class="fas fa-tasks"></i></a></li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        Cerrar sesión <i class="fas fa-sign-out-alt"></i>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>

                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

@yield('content')


</div>
<footer id="p-footer"><!-- footer -->
    <div class="inner">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="address text-center">
                        <ul>
                            <li><i class="icon-address"></i><strong>Direcci&oacute;n:</strong>
								Km 20, carretera Manzanillo - Cihuatlan, ejido El Naranjo, CP. 28868, Manzanillo, Colima, México
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="p-copyright">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-center">
						&copy; Derechos Reservados 2013-2018 Universidad de Colima
                </div>
            </div>
        </div>
    </div>
</footer>


    {{ Html::script('/public/assets/vendor/jquery/dist/jquery.min.js') }}
    {{ Html::script('/public/assets/vendor/bootstrap/dist/js/bootstrap.min.js') }}
    {{ Html::script('/public/js/typeahead.bundle.js') }}
    {{ Html::script('https://use.fontawesome.com/releases/v5.0.1/js/all.js') }}

   	@yield('scripts'){{--Para scripts propios del módulo--}}

</body>
</html>