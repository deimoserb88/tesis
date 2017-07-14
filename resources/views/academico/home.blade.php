@extends('layouts.academico')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Módulos</div>
                <div class="panel-body">
                    <div class="list-group">
                        <a href="/tesis" class="list-group-item">Tesis</a>
                        @if(in_array(Auth::user()->rol,[1,2,3,4,5]))
                            <a href="/usuariosAcademicos" class="list-group-item">Usuarios académicos</a>
                            <a href="/usuariosTesistas" class="list-group-item">Usuarios tesistas</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
