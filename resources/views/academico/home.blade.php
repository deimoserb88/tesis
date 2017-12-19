@extends('layouts.academico')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Actividades</div>
                <div class="panel-body">
                    <div class="list-group">                        
                        <a href="{{ url('/tesis') }}" class="list-group-item"><i class="fas fa-file-alt"></i> Tesis</a>
                        @if(in_array(Auth::user()->priv,[1,2,3,4,5]))
                            <a href="{{ url('/usuariosTesistas') }}" class="list-group-item"><i class="fas fa-graduation-cap"></i> Tesistas </a>
                        @endif
                        @if(in_array(Auth::user()->priv,[1,2]))
                            <a href="{{ url('/usuariosAcademicos') }}" class="list-group-item">Usuarios académicos</a>
                        @endif
                        <a href="{{ url('/logout') }}" class="list-group-item"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                        <a href="{{ url('/usuarioCuenta') }}" class="list-group-item"><i class="fas fa-address-card"></i> Mi cuenta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
