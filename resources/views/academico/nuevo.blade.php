@extends('layouts.academico')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Bienvenido</h4>
                </div>
                <div class="panel-body">
                    Gracias por registrarse en el Sistema de Gestión de Tesis de la FIE.<br>
                    Aún to tiene asignado un rol en el sistema. Para solicitar se le asigna uno, por favor seleccione una de las siguientes opciones y envíe un mensaje de solicitud a la persona correspondiente:
                    <div class="list-group">                        
                        <a href="{{ url('/tesis') }}" class="list-group-item">Tesis <i class="fa fa-btn fa-file-text-o"></i></a>
                        @if(in_array(Auth::user()->priv,[1,2,3,4,5]))
                            <a href="{{ url('/usuariosTesistas') }}" class="list-group-item">Tesistas</a>
                        @endif
                        @if(in_array(Auth::user()->priv,[1,2]))
                            <a href="{{ url('/usuariosAcademicos') }}" class="list-group-item">Usuarios académicos</a>
                        @endif
                        <a href="{{ url('/logout') }}" class="list-group-item">Cerrar sesión <i class="fa fa-btn fa-sign-out"></i></a>
                        <a href="{{ url('/') }}" class="list-group-item">Cambiar contraseña <i class="fa fa-btn fa-key"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
