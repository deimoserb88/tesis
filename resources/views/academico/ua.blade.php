@extends('layouts.academico',['rol'=>min(array_column(session('rol'),'rol'))])

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
                                @if(Auth::user()->priv<3 || $urol[0]->rol <= 4)
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
								<th class="text-right"><i class="fa fa-cog" aria-hidden="true"></i></th>
							</tr>
						</thead>
						<tbody>
							@foreach($u as $ua)
                                <tr>
                                    <td>{{ $ua->nocontrol }}</td>
                                    <td>{{ $ua->nombre }}</td>
                                    <td class="text-right">
                                        @if($urol[0]->rol <= 5 )
                                                          
                                            <div class="btn-group" rol="group">
                                                @if($urol[0]->rol <= 4 )
                                                    @if($ua->priv > Auth::user()->priv)
                                                        <a href="{{ url('/usuarioEditar/'.$ua->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                                    @endif

                                                {{-- @if( Auth::user()->priv == 1 || (strpos($ua->idprograma,strval($urol[0]->idprograma)) !== false)) --}}
                                                    <a href="{{ url('/usuarioRoles/'.$ua->id) }}" class="btn btn-info btn-sm"  data-toggle="tooltip" data-placement="left" title="Definir roles"><i class="fa  fa-cogs"></i></a>
                                                @endif
                                                
                                                @if($tipo_usuario != 9)
                                                    <a href="{{ url('/usuarioTesis/'.$ua->id.'/T') }}" class="btn btn-info btn-sm"  data-toggle="tooltip" data-placement="top" title="Asignar tesis"><i class="fa  fa-bookmark"></i></a>
                                                @endif
                                                @if($ua->priv > Auth::user()->priv)
                                                    <a href="#" class="btn btn-danger btn-sm eliminar" data-nombre="{{ $ua->nombre }}" data-id="{{ $ua->id }}:{{ $ua->priv }}" ><i class="fa fa-trash"></i></a>
                                                @endif
                                                
                                                <a class="btn btn-warning btn-sm em" href="#" data-toggle="modal" data-target="#emensaje" data-usuario="{{ $ua->nombre }}" data-idusuario="{{ $ua->id }}"><i class="far fa-comment"></i></a>
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

@include('academico.partials.usuarionuevo')
@include('academico.partials.mensaje')

@endsection

@section('scripts')
{{ Html::script('/public/assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}
{{ Html::script('/public/assets/vendor/datatables/media/js/dataTables.bootstrap.min.js') }}


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
          $('.genprog').removeClass('hidden');
        }else{
          $('.genprog').addClass('hidden');
        }


        $('#priv').change(function(){
          var p = parseInt($(this).val());
          if(p === 5){
            $('.genprog').removeClass('hidden');
          }else{
            $('.genprog').addClass('hidden');
          }
        });
    });

</script>

@endsection
