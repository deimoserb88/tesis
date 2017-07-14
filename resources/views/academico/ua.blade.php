@extends('layouts.academico')

@section('estilos')
{{ Html::style('/public/assets/vendor/datatables/media/css/dataTables.bootstrap.min.css') }}	
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                	<div class="row">
                		<div class="col-md-8">
                			<h4 style="display: inline;">Usuarios {{ tipoUsuario($tipo_usuario) }}</h4> | 
                            <span style="color: white;">
                            @if(Auth::user()->rol<=5)
                                @if($tipo_usuario == 9)
                                    <a href="{{ url('/usuariosAcademicos') }}" class="btn btn-xs">Ver usuarios académicos</a>
                                @else
                                    <a href="{{ url('/usuariosTesistas') }}" class="btn btn-xs">Ver usuarios tesistas</a>
                                @endif
                            @endif
                            </span>
                		</div>
                		<div class="col-md-4 text-right">
                            @if(Auth::user()->rol <= 6)
                		      <button class="btn btn-default btn-xs" data-toggle="modal" data-target="#nuevousuario">Nuevo <i class="fa fa-btn fa-user-plus"></i></button>
                            @endif
                		</div>
                	</div>                	
                </div>
                <div class="panel-body">
					<table class="table table-striped" id="tua">
						<thead>
							<tr>
								<th>No.</th>
								<th>Nombre</th>
								<th class="text-center"><i class="fa fa-cog" aria-hidden="true"></i></th>
							</tr>
						</thead>
						<tbody>
							@foreach($u as $ua)
								<tr>
									<td>{{ $ua->nocontrol }}</td>
									<td>{{ $ua->nombre }}</td>
									<td class="text-center">
										<a href="{{ url('/usuarioEditar/'.$ua->id) }}" class="btn btn-link btn-xs"><i class="fa fa-btn fa-pencil"></i></a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
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
                                <button class="btn btn-link" data-toggle="modal" data-target="#nuevousuario">Corregir</button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para agregar un nuevo registro de usuario --}}

<div class="modal fade" tabindex="-1" role="dialog" id="nuevousuario">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('usuarioGuardar') }}" method="post" id="usuarioGuardar"  class="form-horizontal">
             {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar usuario</h4>
                </div>
                <div class="modal-body">
                    
                  <div class="form-group{{ isset($errores)?($errores->has('nombre') ? ' has-error' : ''):'' }}">
                    <label for="nombre" class="col-sm-4 control-label">Nombre completo</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="nombre" name="nombre" required="required" autofocus="autofocus" value="{{ isset($request)?$request->nombre:'' }}">
                    </div>
                  </div>                    
                  <div class="form-group{{ isset($errores)?($errores->has('nocontrol') ? ' has-error' : ''):''  }}">
                    <label for="nocontrol" class="col-sm-4 control-label">Número de {{ $tipo_usuario<9?'trabajador':'cuenta' }}</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="nocontrol" name="nocontrol" required="required" maxlength="5" value="{{ isset($request)?$request->nocontrol:'' }}">
                    </div>
                  </div>                    
                  <div class="form-group{{ isset($errores)?($errores->has('email') ? ' has-error' : ''):'' }}">
                    <label for="email" class="col-sm-4 control-label">Correo electrónico</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="email" name="email" required="required" value="{{ isset($request)?$request->email:'' }}">
                    </div>
                  </div>                    
                  <div class="form-group{{ isset($errores)?($errores->has('password') ? ' has-error' : ''):'' }}">
                    <label for="password" class="col-sm-4 control-label">Contraseña</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="password" name="password" required="required">
                    </div>
                  </div>
                    @if($tipo_usuario < 9)
                      <div class="form-group{{ isset($errores)?($errores->has('rol') ? ' has-error' : ''):'' }}">
                        <label for="password" class="col-sm-4 control-label">Rol</label>
                        <div class="col-sm-8">
                            <select name="rol" class="form-control" id="rol">
                                @foreach(range(Auth::user()->rol+1,9) as $r)
                                    <option value="{{ $r }}" {{ isset($request)?($r == $request->rol?'selected="selected"':''):'' }}>{{ tesis\User::rol($r) }}</option>
                                @endforeach
                            </select>
                        </div>
                      </div>
                    @else
                        <input type="hidden" name="rol" value="9">
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



@endsection

@section('scripts')
{{ Html::script('/public/assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}
{{ Html::script('/public/assets/vendor/datatables/media/js/dataTables.bootstrap.min.js') }}	

<script type="text/javascript">

    $(document).ready(function() {

        $('#tua').DataTable({
            "scrollY": 480,
            "scrollCollapse": true,
            "paging": false,
            "info": false,
            "language": {
                "search": "Filtrar:",
                "zeroRecords": "No se encontraron registros que coincidan",
            },
            "select": true,
            "emptyTable" : "No hay datos para mostrar",
            "columnDefs": [
                { "orderable": false, "targets": 2 }
            ],             
        });

        $('.close').click(function(){
            $('.has-error').removeClass('has-error');
            var campos = $('#usuarioGuardar input:not([type="hidden"])');
            $('#usuarioGuardar').find(campos).val('');
        });

    });

</script>

@endsection
