@extends('layouts.academico',['rol'=>$urol[0]->rol])

@section('estilos')
{{ Html::style('/public/assets/vendor/datatables/media/css/dataTables.bootstrap.min.css') }}
{{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css') }}
@endsection

@section('content')
<div class="container" id="app">
    <div class="row">
        <div class="col-md-12">
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
                		      <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#nuevousuario">Nuevo <i class="fa fa-btn fa-user-plus"></i></button>
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
                                    foreach($urol as $roles){
                                        $rolvalido = $rolvalido || ($usuario->idprograma == $roles['idprograma'] && $roles['rol'] <= 5);
                                    }
                                @endphp
								<tr>
									<td>{{ $usuario->nocontrol }}</td>
                                    <td>{{ $usuario->nombre }}</td>
                                    <td>
                                        @if((Auth::user()->priv<=3 && $rolvalido) || Auth::user()->priv == 1)
                                            @if(!is_null($usuario->idprograma))
                                            <a href="#" class="carrera" data-pk="{{ $usuario->id }}">
                                                @foreach($p as $carr)
                                                    @if($carr->id == $usuario->idprograma)
                                                        {{ $carr->abrev }}
                                                    @endif
                                                @endforeach
                                            </a>
                                            @endif
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
                                        <div class="btn-group" rol="group">
                                        @if(Auth::user()->priv <= 3 )
                                                @if($rolvalido || Auth::user()->priv == 1)
                                                    <a href="{{ url('/usuarioEditar/'.$usuario->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                                @endif
                                                <a href="#" class="btn btn-info btn-sm dt {{ $usuario->idtesis==''?'disabled':'' }}" data-idtesis="{{ $usuario->idtesis }}"  data-nombre="{{ $usuario->nombre }}"><i class="fas fa-file-alt"></i></a>

                                                @if($rolvalido || Auth::user()->priv == 1)
                                                    <a href="#" class="btn btn-danger btn-sm eliminar" data-nombre="{{ $usuario->nombre }}" data-id="{{ $usuario->id }}:{{ $usuario->priv }}" ><i class="fa fa-trash"></i></a>
                                                @endif
                                            
                                        @else
								            
                                                @if($usuario->idtesis != '')
                                                    <a href="#" class="btn btn-info btn-sm dt" data-idtesis="{{ $usuario->idtesis }}" data-nombre="{{ $usuario->nombre }}"><i class="far fa-file-alt"></i></a>
                                                @else
                                                    <a href="#" class="btn btn-info btn-sm disabled"><i class="far fa-file-alt"></i></a>
                                                @endif
                                        @endif
                                            <a class="btn btn-warning btn-sm em" href="#" data-toggle="modal" data-target="#emensaje" data-usuario="{{ $usuario->nombre }}" data-idusuario="{{ $usuario->id }}"><i class="far fa-comment"></i></a>
                                        </div>
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
                        <button type="button" class="btn btn-danger cancelar" data-dismiss="modal">Cancelar <i class="fas fa-times"></i></button>
                        <button type="submit" class="btn btn-success">Guardar <i class="fas fa-check"></i></button>
                    </div>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


@include('academico.partials.detalletesista');

@include('academico.partials.emensaje');

@endsection

@section('scripts')
{{ Html::script('/public/assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}
{{ Html::script('/public/assets/vendor/datatables/media/js/dataTables.bootstrap.min.js') }}
{{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js') }}

<script type="text/javascript">

    $(document).ready(function() {
        $('.em').click(function(){
            var em = $(this);
            $('.nombre-usuario').text('Mensaje para: ' + em.data('usuario'));
            $('#idusuario').val(em.data('idusuario'));
            $('#mensaje').val("");
        });

        $('#idusuario').click(function(e){
            var idu = $(this);
            var em = $.post(
                        "{{ url('enviarMensaje') }}",
                        {
                            idusuario:idu.val(),
                            mensaje:$('#mensaje').val(),
                            _token:$('meta[name="csrf-token"]').attr("content")
                        }
                    );
            em.done(function(resp){
                alert('Mensaje enviado');
            });
            em.always(function(resp){
                //console.log(resp);
            });
            $('#emensaje').modal('hide');
        });

        $('.guardar').click(function(){
            $('.tesistaProGen').trigger('submit');
        });

        $('#idprograma').change(function(){
            if($(this).val() !== ""){
                $('.guardar').removeClass('disabled');
            }
        });

        $.fn.editable.defaults.mode = 'popup';
        $.fn.editable.defaults.placement = 'left';
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
            "scrollCollapse": false,
            "paging": false,
            "info": false,
            "language": {
                "search"    : "Filtrar:",
                "lengthMenu": "Mostrar _MENU_ registros",
                "info"      : "Mostrando del _START_ al _END_ de _TOTAL_",
                "paginate"  : { 
                    "first"   : '<i class="fas fa-angle-double-left"></i>',
                    "last"    : '<i class="fas fa-angle-double-right"></i>',
                    "previous": '<i class="fas fa-angle-left"></i>',
                    "next"    : '<i class="fas fa-angle-right"></i>'
                },                
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
            var r = $.post(
                        "{{ url('/getTesisDetalle') }}",
                        {
                            idtesis:dt.data("idtesis"),
                            _token:$('meta[name="csrf-token"]').attr("content")
                        }
                    );
            var tesis = asesor = coasesores = revisores = tsts = '';
            r.done(function(resp){
                console.log(resp);
                $('.dtitulo').html('<strong>'+resp.tesis[0].nom+'</strong>');
                $('.ddescripcion').html('<strong>'+resp.tesis[0].desc+'</strong>');
                resp.docentes.forEach(function(v){
                    switch(Number(v.rol)){
                        case 6: asesor = v.nombre;break;
                        case 7: coasesores = v.nombre + ', ' + coasesores;break;
                        case 8: revisores = v.nombre + ', ' + revisores;break;
                    }
                });
                $('.dasesor').html('<strong>'+asesor+'</strong>');
                $('.dcoasesores').html(coasesores.length>0?'<strong>'+coasesores+'</strong>':'<em class="text-muted">No definido</em>');
                $('.drevisores').html(revisores.length>0?'<strong>'+revisores+'</strong>':'<em class="text-muted">No definidos</em>');
                $('.destado').html('<strong>'+['','','','Asignada','Concluida'][resp['tesis'][0].estado]+'</strong>');

                resp.tesistas.forEach(function(v){
                    tsts = v.nombre + ', ' + tsts;
                });
                $('.nombre-tesista').html(tsts);
            });
            $('#detalletesista').modal('toggle');

        });

    });

</script>

@endsection
