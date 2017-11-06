@extends('layouts.academico')

@section('estilos')
{{ Html::style('/public/assets/vendor/datatables/media/css/dataTables.bootstrap.min.css') }}    
{{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css') }}	
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                	<div class="row">
                		<div class="col-md-9">
                            <h4 style="display: inline;">Tesistas</h4>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default">
                                @if($gen == '')
                                    Todas las generaciones
                                @else
                                    {{ $gen }}
                                @endif
                                </button>
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>                                    
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach($g as $gene)
                                        <li><a href="{{ url('/usuariosTesistas/'.$gene->gen) }}">{{ $gene->gen }}</a></li>
                                    @endforeach
                                    <li role="separator" class="divider"></li>
                                    <li><a href="{{ url('/usuariosTesistas') }}">Todas las generaciones</a></li>

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
								<th>No. Cta.</th>
                                <th>Nombre</th>
								<th>Carrera</th>
                                <th>Generación</th>
								<th class="text-center"><i class="fa fa-cog" aria-hidden="true"></i></th>
							</tr>
						</thead>
						<tbody>
							@foreach($u as $usuario)
                                {{-- solo con rol 5 o menor en el programa del tesista puede hacer cambios --}}
                                @php
                                    $rolvalido = false;
                                    foreach($rol as $roles){
                                        $rolvalido = $rolvalido || ($usuario->idprograma == $roles['idprograma'] && $roles['rol'] <= 5);
                                    }
                                @endphp                            
								<tr>
									<td>{{ $usuario->nocontrol }}</td>
                                    <td>{{ $usuario->nombre }}</td>									
                                    <td>
                                        @if((Auth::user()->priv<=3 && $rolvalido) || Auth::user()->priv == 1)
                                            <a href="#" class="carrera" data-pk="{{ $usuario->id }}">
                                            @if(!is_null($usuario->idprograma))
                                                @foreach($p as $carr)
                                                    @if($carr->id == $usuario->idprograma)
                                                        {{ $carr->abrev }}
                                                    @endif
                                                @endforeach
                                            @endif
                                            </a>
                                        @else
                                            @php
                                                $x = false;
                                                foreach($p as $carr){
                                                    if($carr->id == $usuario->idprograma){
                                                        echo $carr->abrev;
                                                        $x = true;
                                                    }                                                
                                                }
                                                if(!$x){
                                                    echo 'ND';
                                                }
                                            @endphp
                                        @endif
                                    </td>                                    
                                    <td>
                                        @if((Auth::user()->priv<=3 && $rolvalido) || Auth::user()->priv == 1)
                                            @if(!is_null($usuario->idprograma))
                                                <a href="#" class="gen" data-pk="{{ $usuario->id }}">
                                                @if($usuario->gen!='')
                                                    {{ $usuario->gen }}
                                                @endif
                                                </a>
                                            @else
                                                <a href="#" data-toggle="tooltip" data-placement="right" title="Defina primero la carrera">
                                                    <i class="fa fa-btn fa-caret-left"></i>
                                                    <i class="fa fa-btn fa-question-circle"></i>
                                                </a>
                                            @endif
                                        @else
                                            @if($usuario->gen!='')
                                                {{ $usuario->gen }}
                                            @else
                                                ND
                                            @endif                                        
                                        @endif
                                    </td>                                    
									<td>
                                        @if(Auth::user()->priv <= 3 )
                                            <div class="btn-group" rol="group">                                                
                                                <a href="{{ url('/usuarioEditar/'.$usuario->id) }}" class="btn btn-info btn-sm"><i class="fa  fa-pencil"></i></a>
                                                <a href="#" class="btn btn-info btn-sm dt {{ $usuario->idtesis==''?'disabled':'' }}" data-idtesis="{{ $usuario->idtesis }}"  data-nombre="{{ $usuario->nombre }}"><i class="fa fa-file-text"></i></a> 
                                                

                                                @if($rolvalido || Auth::user()->priv == 1)
                                                    <a href="#" class="btn btn-danger btn-sm eliminar" data-nombre="{{ $usuario->nombre }}" data-id="{{ $usuario->id }}:{{ $usuario->priv }}" ><i class="fa fa-trash"></i></a>
                                                @endif                                                
                                            </div>                                       
                                        @else
								            <div class="btn-group" rol="group">                                                
                                                <a href="#" class="btn btn-info btn-sm dt" data-idtesis="{{ $usuario->idtesis }}" data-nombre="{{ $usuario->nombre }}"><i class="fa fa-file-text"></i></a>
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
                    <h5><span class="label label-info"><strong>ND</strong> - Valor no definido</span></h5>
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
                    <label for="nocontrol" class="col-sm-4 control-label">Número de cuenta</label>
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
                            <label>Tesista</label>
                            <input type="hidden" name="priv" value="5">                                
                        </div>
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


{{-- Modal para ver detalles del tesista: titrulo de tesis, descripcion, asesor, coasesro, etc. --}}

<div class="modal fade" tabindex="-1" role="dialog" id="detalletesista">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close cancelar" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title nombre-tesista"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-2 etitulo">Titulo:</div>
                        <div class="col-sm-10 dtitulo"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 edescripcion">Descripción:</div>
                        <div class="col-sm-10 ddescripcion"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 easesor">Asesor:</div>
                        <div class="col-sm-10 dasesor"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 eestado">Estado:</div>
                        <div class="col-sm-10 destado"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="button" class="btn btn-success btn-sm" data-dismiss="modal"><i class="fa fa-check"></i></button>                        
                    </div>
                </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



@endsection

@section('scripts')
{{ Html::script('/public/assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}
{{ Html::script('/public/assets/vendor/datatables/media/js/dataTables.bootstrap.min.js') }} 
{{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js') }}	

<script type="text/javascript">

    $(document).ready(function() {


        $.fn.editable.defaults.mode = 'inline';
        $.fn.editable.defaults.params = function(params){
            params._token = $('meta[name="csrf-token"]').attr("content");
            return params;
        };        
        $(".carrera").editable({
            type: 'select',
            source: [
                    @foreach($p as $carr)
                        {
                        'value': {{ $carr->id }}, 'text': '{{ $carr->abrev }}'
                        },
                    @endforeach                
                    ],
            name: 'idprograma',
            emptytext: 'ND',
            pk: $(this).attr("data-pk"),
            url: '{{ route('asignaCarr') }}',
            title: 'Asignar carrera'
        });

        $(".gen").editable({
            type: 'number',
            name: 'gen',
            emptytext: 'ND',
            pk: $(this).attr("data-pk"),
            url: '{{ route('asignaGen') }}',
            title: 'Asignar generación'
        });
       

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
                { "orderable": false, "targets": 4 }
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

        $('[data-toggle="tooltip"]').tooltip()

        $('.dt').click(function(){
            var dt = $(this);
            $('.nombre-tesista').text(dt.data('nombre'));
            var r = $.post(
                        "{{ url('getTesisDetalle') }}",
                        {
                            idtesis:dt.data("idtesis"),
                            _token:$('meta[name="csrf-token"]').attr("content")
                        }
                    );
            
            r.done(function(resp){
                
                $('.dtitulo').html('<strong>'+resp[0].nom+'</strong>');
                $('.ddescripcion').html('<strong>'+resp[0].desc+'</strong>');
                $('.dasesor').html('<strong>'+resp[1].nombre+'</strong>');
                $('.destado').html('<strong>'+resp[0].estado+'</strong>');
                
            });  

            $('#detalletesista').modal('toggle');

        });

    });

</script>

@endsection
