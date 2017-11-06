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
    {{-- Html::style('public/assets/vendor/bootstrap/dist/css/bootstrap.min.css') --}}  
    {{ Html::style('public/css/app.css') }}	
    {{ Html::style('http://www.ucol.mx/cms/headerfooterapp.css') }}	 {{-- CSS de la universidad --}}
    {{ Html::style('https://fonts.googleapis.com/css?family=Lato:100,300,400,700') }}<!-- Fonts -->
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css') }}<!-- Iconos -->    

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
                <a class="navbar-brand" href="{{ url('/') }}">
                    TESIS <i class="fa fa-btn fa-home"></i>
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                @if (Auth::check())
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/tesis') }}">Tesis<i class="fa fa-btn fa-file-text-o"></i></a></li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/usuariosTesistas') }}">Tesistas<i class="fa fa-btn fa-graduation-cap"></i></a></li>
                    </ul>
                @endif
        

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    @yield('menu_items')
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Iniciar sesión<i class="fa fa-btn fa-sign-in"></i></a></li>
                        <li><a href="{{ url('/register') }}">Registrarse <i class="fa fa-btn fa-user-plus"></i></a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ explode(" ",Auth::user()->nombre)[0]." (".Auth::user()->priv.")" }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                @if(Auth::user()->priv < 5)
                                    <li><a href="{{ url('/academicoHome') }}">Panel de actividades <i class="fa fa-btn fa-tasks"></i></a></li>
                                @else
                                    <li><a href="{{ url('/tesistaHome') }}">Panel de actividades <i class="fa fa-btn fa-tasks"></i></a></li>									
                                @endif
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            Cerrar sesión <i class="fa fa-btn fa-sign-out"></i>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>

                                </li>
                            </ul>
                        </li>
                    @endif
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
								&copy; Derechos Reservados 2013-2017 Universidad de Colima
                        </div>                        
                    </div>
                </div>
            </div>
        </footer>
	
    {{ Html::script('/public/assets/vendor/jquery/dist/jquery.min.js') }}
    {{ Html::script('/public/assets/vendor/bootstrap/dist/js/bootstrap.min.js') }}

   	@yield('scripts'){{--Para scripts propios del módulo--}}	

</body>
</html>