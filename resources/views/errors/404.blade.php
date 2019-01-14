@extends('layouts.error')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h4 class="text-success">BIENVENIDO</h4></div>
                <div class="panel-body text-center">
                    <h3>404, el recurso que está buscando no se encuentra en el servidor</h3>
                    <hr>
                    <a href="{{ route('home') }}" class="btn btn-default">Inicio <i class="fa fa-btn fa-home"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
