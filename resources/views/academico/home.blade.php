@extends('layouts.academico',['rol'=>$urol])

@section('content')
<div class="container" id="app">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Actividades</div>
                <div class="panel-body">
                    <div class="list-group">
                        <a href="{{ url('/tesis') }}" class="list-group-item"><i class="fas fa-file-alt"></i> Tesis</a>
                        @if(Auth::user()->priv < 5)
                            <a href="{{ url('/usuariosTesistas') }}" class="list-group-item"><i class="fas fa-graduation-cap"></i> Tesistas </a>
                        @endif
                        @if(Auth::user()->priv == 1 || in_array($urol,[1,2,3,4]))
                            <a href="{{ url('/usuariosAcademicos') }}" class="list-group-item"><i class="fas fa-users"></i> Usuarios académicos </a>
                        @endif
                            <a href="{{ route('logout') }}" class="list-group-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                        <a href="{{ url('/usuarioCuenta') }}" class="list-group-item"><i class="fas fa-address-card"></i> Mi cuenta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
