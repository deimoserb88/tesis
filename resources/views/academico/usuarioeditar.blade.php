@extends('layouts.academico')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        	{{-- Se envian los parametros 'u' por Update y 'a' o 't' para identifivcar si se trata de academico o tesista --}}
        	<form action="{{ url('usuarioGuardar/u/'.($u->first()->priv<5?'a':'t')) }}" method="post" id="usuarioGuardar"  class="form-horizontal">
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
                      <input type="email" class="form-control" id="email" name="email" required="required" value="{{ $u->first()->email }}" placeholder="Debe ser institucional">
                    </div>
                  </div>
                  <div class="form-group{{ isset($errores)?($errores->has('login') ? ' has-error' : ''):'' }}">
                    <label for="login" class="col-sm-4 control-label">Login (usuario)</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="login" name="login" required="required" value="{{ $u->first()->login }}">
                    </div>
                  </div>

                  <div class="form-group{{ isset($errores)?($errores->has('priv') ? ' has-error' : ''):'' }}">
                    <label for="password" class="col-sm-4 control-label">Tipo de usuario</label>
                    <div class="col-sm-8">
                        @if(strlen($u->first()->nocontrol) == 4)
                          @if(Auth::user()->priv <= 2){{-- Solo si tiene privilegios 1 o 2 puede cambiar los privilegios del usuario --}}
                          <select name="priv" class="form-control" id="priv">
                              @foreach(range(Auth::user()->priv+1,4) as $prv)
                                  <option value="{{ $prv }}" {{ ($prv == $u->first()->priv?'selected="selected"':'') }}>{{ tesis\User::priv($prv) }}</option>
                              @endforeach
                          </select>
                          @else
                              {{ tesis\User::priv($u->first()->priv) }}
                          @endif
                        @else
                          <input type="hidden" name="priv" value="5">
                          <h4>Tesista</h4>
                        @endif
                    </div>
                  </div>
                  <div class="form-group {{ (strlen($u->first()->nocontrol) == 4?'hidden':'') }} genprog">
                    <label for="carr" class="col-sm-4 control-label">Programa</label>
                    <div class="col-sm-8">
                        <select name="carr" class="form-control" id="carr">
                            @foreach($p as $prog)
                                <option value="{{ $prog->id }}">{{ $prog->programa }}</option>
                            @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="form-group {{ (strlen($u->first()->nocontrol) == 4?'hidden':'') }} genprog">
                    <label for="gen" class="col-sm-4 control-label">Generación</label>
                    <div class="col-sm-2">
                        <select name="gen" class="form-control" id="gen">
                                <option value="{{ date("Y") }}">{{ date("Y") }}</option>
                                <option value="{{ date("Y")+1 }}">{{ date("Y")+1 }}</option>
                                <option value="{{ date("Y")+2 }}">{{ date("Y")+2 }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">&nbsp;</div>
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
		                    	<button type="button" class="btn btn-danger cancelar">Cancelar <i class="fas fa-times"></i> </button>
		                    	<button type="submit" class="btn btn-success">Guardar <i class="fas fa-check"></i></button>
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
          @if($u->first()->priv == 5)
        	   window.location.href = '{{ url('/usuariosTesistas') }}';
          @else
             window.location.href = '{{ url('/usuariosAcademicos') }}';
          @endif
        });

        var p = parseInt($('#priv').val());
        if(p === 5){
          $('.gen').removeClass('hidden');
        }else{
          $('.gen').addClass('hidden');
        }


        $('#priv').change(function(){
          var p = parseInt($(this).val());
          if(p === 5){
            $('.gen').removeClass('hidden');
          }else{
            $('.gen').addClass('hidden');
          }
        });

    });

</script>

@endsection
