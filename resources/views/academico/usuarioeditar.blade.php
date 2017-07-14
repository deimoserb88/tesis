@extends('layouts.academico')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        	{{-- Se envian los parametros 'u' por Update y 'a' o 't' para identifivcar si se trata de academico o tesista --}}	
        	<form action="{{ url('usuarioGuardar/u/'.($u->first()->rol<9?'a':'t')) }}" method="post" id="usuarioGuardar"  class="form-horizontal">
             {{ csrf_field() }}
             <input type="hidden" name="id" value="{{ $u->first()->id }}">
            <div class="panel panel-primary">
                <div class="panel-heading">
                	              		
                		<h5>Editar registro de usuario</h5>                	
                               	
                </div>
                <div class="panel-body">
                  <div class="form-group{{ isset($errores)?($errores->has('nombre') ? ' has-error' : ''):'' }}">
                    <label for="nombre" class="col-sm-4 control-label">Nombre completo</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="nombre" name="nombre" required="required" autofocus="autofocus" value="{{ $u->first()->nombre }}">
                    </div>
                  </div>                    
                  <div class="form-group{{ isset($errores)?($errores->has('nocontrol') ? ' has-error' : ''):''  }}">
                    <label for="nocontrol" class="col-sm-4 control-label">Número de trabajador/cuenta</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="nocontrol" name="nocontrol" required="required" maxlength="8" value="{{ $u->first()->nocontrol }}">
                    </div>
                  </div>                    
                  <div class="form-group{{ isset($errores)?($errores->has('email') ? ' has-error' : ''):'' }}">
                    <label for="email" class="col-sm-4 control-label">Correo electrónico</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="email" name="email" required="required" value="{{ $u->first()->email }}">
                    </div>
                  </div>                    
                  <div class="form-group{{ isset($errores)?($errores->has('login') ? ' has-error' : ''):'' }}">
                    <label for="login" class="col-sm-4 control-label">Login (usuario)</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="login" name="login" required="required" value="{{ $u->first()->login }}">
                    </div>
                  </div>
                    
                  <div class="form-group{{ isset($errores)?($errores->has('rol') ? ' has-error' : ''):'' }}">
                    <label for="password" class="col-sm-4 control-label">Rol</label>
                    <div class="col-sm-8">
                        <select name="rol" class="form-control" id="rol">
                            @foreach(range(Auth::user()->rol+1,9) as $r)
                                <option value="{{ $r }}" {{ ($r == $u->first()->rol?'selected="selected"':'') }}>{{ tesis\User::rol($r) }}</option>
                            @endforeach
                        </select>
                    </div>
                  </div>

                </div>					
                <div class="panel-footer">
                    @if(isset($errores))
                        @if($errores->any())
                    	   <div id="errores" class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4>Hubo errores</h4>
                                <ul>
                                @foreach ($errores->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach                                    
                                </ul>                                
                            </div>
                        @endif
                    @endif                	
                	<div class="row">
	                	<div class="col-md-4 col-md-offset-8">
		                	<div class="btn-group" role="group" aria-label="">
		                    	<button type="button" class="btn btn-danger cancelar" data-dismiss="modal">Cancelar <i class="fa fa-btn fa-close"></i> </button>
		                    	<button type="submit" class="btn btn-success">Guardar <i class="fa fa-btn fa-check"></i></button>
		                    </div>
	                    </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">

    $(document).ready(function() {

        $('.cancelar').click(function(){
          @if($u->first()->rol < 9)
             window.location.href = '{{ url('/usuariosAcademicos') }}';
          @else
        	   window.location.href = '{{ url('/usuariosTesistas') }}';
          @endif
        });

    });

</script>

@endsection
