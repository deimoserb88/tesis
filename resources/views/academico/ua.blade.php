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
                		<div class="col-md-9">
                			<h4 style="display: inline;">Usuarios </h4>                            
                            <div class="btn-group">
                                <button type="button" class="btn btn-default">{{ tipoUsuario($tipo_usuario) }}s</button>
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>                                    
                                </button>
                                <ul class="dropdown-menu">
                                @if(Auth::user()->priv<3)
                                    <li><a href="{{ url('/usuariosAcademicos') }}">Ver académicos</a></li>
                                @endif
                                @if(Auth::user()->priv<=3)                                    
                                    <li><a href="{{ url('/usuariosNuevos') }}">Ver nuevos</a></li>
                                @endif
                                </ul>
                            </div>            


                		</div>
                		<div class="col-md-3 text-right">
                            @if(Auth::user()->priv <= 3 )
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
                                        @if(Auth::user()->priv <=3 )
								            <div class="btn-group" rol="group">                                                
                                                <a href="{{ url('/usuarioEditar/'.$ua->id) }}" class="btn btn-info btn-sm"><i class="fa  fa-pencil"></i></a>
                                                <a href="{{ url('/usuarioRoles/'.$ua->id) }}" class="btn btn-info btn-sm"  data-toggle="tooltip" data-placement="right" title="Definir roles"><i class="fa  fa-cogs"></i></a>
                                                <a href="{{ url('/usuarioTesis/'.$ua->id.'/T') }}" class="btn btn-info btn-sm"  data-toggle="tooltip" data-placement="left" title="Asignar tesis"><i class="fa  fa-bookmark"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm eliminar" data-nombre="{{ $ua->nombre }}" data-id="{{ $ua->id }}:{{ $ua->priv }}" ><i class="fa fa-trash"></i></a>
                                            
                                            </div>                                       
                                        @endif
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
                    <button type="button" class="close cancelar" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
                    <label for="nocontrol" class="col-sm-4 control-label">Número de {{ $tipo_usuario<5?'trabajador':'cuenta' }}</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="nocontrol" name="nocontrol" required="required" maxlength="8" value="{{ isset($request)?$request->nocontrol:'' }}">
                    </div>
                  </div>                    
                  <div class="form-group{{ isset($errores)?($errores->has('email') ? ' has-error' : ''):'' }}">
                    <label for="email" class="col-sm-4 control-label">Correo electrónico</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="email" name="email" required="required" value="{{ isset($request)?$request->email:'' }}" placeholder="Debe ser institucional (@ucol.mx)">
                    </div>
                  </div>                    
                  <div class="form-group{{ isset($errores)?($errores->has('password') ? ' has-error' : ''):'' }}">
                    <label for="password" class="col-sm-4 control-label">Contraseña</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="password" name="password" required="required">
                    </div>
                  </div>
           
                      <div class="form-group{{ isset($errores)?($errores->has('priv') ? ' has-error' : ''):'' }}">
                        <label for="password" class="col-sm-4 control-label">Tipo usuario</label>
                        <div class="col-sm-8">
                            @if(Auth::user()->priv<3)
                            <select name="priv" class="form-control" id="priv">
                                @foreach(range(Auth::user()->priv+1,5) as $r)
                                    <option value="{{ $r }}" {{ isset($request)?($r == $request->priv?'selected="selected"':''):'' }}>{{ tesis\User::priv($r) }}</option>
                                @endforeach
                            </select>
                            @else
                                <label>{{ tipoUsuario($tipo_usuario) }}</label>
                                <input type="hidden" name="priv" value="{{ $tipo_usuario }}">    
                            @endif
                        </div>
                      </div>
                      <div class="form-group hidden gen">
                        <label for="gen" class="col-sm-4 control-label">Generación</label>
                        <div class="col-sm-3">
                            <select name="gen" class="form-control" id="gen">                            
                                    <option value="{{ date("Y") }}">{{ date("Y") }}</option>                            
                                    <option value="{{ date("Y")+1 }}">{{ date("Y")+1 }}</option>                            
                                    <option value="{{ date("Y")+2 }}">{{ date("Y")+2 }}</option>                            
                            </select>
                        </div>
                        <div class="col-md-5">&nbsp;</div>
                      </div>                      


                </div>
                <div class="modal-footer">
                    <div class="btn-group" rol="group">
                        <button type="button" class="btn btn-danger cancelar" data-dismiss="modal">Cancelar <i class="fa fa-btn fa-close"></i></button>
                        <button type="submit" class="btn btn-success">Guardar <i class="fa fa-btn fa-check"></i></button>
                    </div>                    
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

        $('.cancelar').click(function(){
            $('.has-error').removeClass('has-error');
            var campos = $('#usuarioGuardar input:not([type="hidden"])');
            $('#usuarioGuardar').find(campos).val('');
        });


        $(".eliminar").click(function(){
            if(confirm('Esta seguro de eliminar a '+$(this).attr('data-nombre'))){
               window.location.href="{{ url('/usuarioEliminar') }}/"+$(this).attr('data-id');
            }
        });

        $('[data-toggle="tooltip"]').tooltip();

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
