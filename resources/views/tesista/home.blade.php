@extends('layouts.tesista')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Actividades</div>
                <div class="panel-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            <h4 class="list-group-item-heading"><i class="fas fa-file-alt"></i> Tesis</h4>
                            <p class="list-group-item-text">
                                <table class="table table-hover"> 
                                        <tr>
                                            <td>Título</td>
                                            <td>{{ $t->first()->nom }} ({{ $t->first()->id }})</td>
                                            <td>
                                                <div class="btn-group">
                                                  <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fab fa-google-drive"></i>&nbsp;&nbsp;<i class="fa fa-file-pdf"></i>&nbsp;&nbsp;<i class="caret"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">
                                                    <li><a href="#" data-toggle="modal" data-target="#enlacegdrive"><i class="fas fa-link"></i> Agregar enlace de Google Drive</a></li>
                                                    <li class="{{ !is_null($t->first()->urldoc)?'':'disabled' }}" id="abrirUrl"><a href="{{ $t->first()->urldoc }}" target="_blank"><i class="fab fa-google-drive"></i> Abrir documento en Google Drive</a></li>
                                                    <li role="separator" class="divider"></li>
                                                    <li><a href="{{ url('tesisSubirPdf')."/".$t->first()->id }}"><i class="fas fa-upload"></i> Subir archivo PDF</a></li>
                                                    <li class="disabled"><a href="#"><i class="far fa-file-pdf"></i> Abrir archivo PDF</a></li>
                                                  </ul>
                                                </div>                                                
                                            </td>
                                        </tr>
                                        <tr><td>Descripción</td><td>{{ $t->first()->desc }}</td><td>&nbsp;</td></tr>
                                        <tr><td>Generación</td><td>{{ $t->first()->gen }}</td><td>&nbsp;</td></tr>
                                        @foreach($acr as $asesor)
                                            <tr>
                                                <td>{{ tesis\Rol::rol($asesor->rol) }}</td>
                                                <td>{{ $asesor->nombre }}</td>
                                                <td>
                                                    <div class="btn-group" rol="group">
                                                        <a href="#" class="btn btn-warning btn-xs em" data-usuario="{{ $asesor->nombre }}" data-idusuario="{{ $asesor->id }}" data-toggle="modal" data-target="#emensaje"><i class="far fa-comment" ></i></a>
                                                        <a href="#" data-email="{{ $asesor->email }}" class="btn btn-success btn-xs email">
                                                            <i class="fas fa-envelope"></i>
                                                            <i class="fas fa-long-arrow-alt-right"></i>
                                                            <i class="fas fa-clipboard"></i>
                                                        </a>
                                                    </div>                                                    
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr><td>Tesistas</td><td>
                                            @foreach($tsts as $tesistas)
                                            {{ $tesistas->nombre }},
                                            @endforeach
                                        </td><td>&nbsp;</td></tr>
                                </table>
                            </p>
                        </div>
                        <a href="{{ route('logout') }}" class="list-group-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                        <a href="{{ url('/usuarioCuenta') }}" class="list-group-item"><i class="fas fa-address-card"></i> Mi cuenta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="emensaje">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title nombre-usuario"></h4>
          </div>
          <div class="modal-body">
            <textarea name="mensaje" class="form-control" id="mensaje" cols="70" rows="5"></textarea>
          </div>
          <div class="modal-footer">
            <div class="btn-group" rol="group">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar <i class="fas fa-times"></i></button>
                <button type="button" class="btn btn-success" id="idusuario" value="">Enviar <i class="fab fa-telegram-plane"></i> </button>
            </div>
          </div>
        </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="enlacegdrive">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Enlace al documento de Google Drive</h4>
          </div>
          <div class="modal-body">
            <div class="alert alert-warning">
                En Goggle Drive, seleccione la opción de compartir el enlace al documento y péguelo completo en el siguiente espacio. Si ya tiene un enlace anterior puede borrarlo e ingresar el nuevo.
            </div> 
            <textarea rows="5" class="form-control" name="urldoc" id="urldoc" placeholder="URL del documento en Google Drive">
                {{ $t->first()->urldoc }}
            </textarea>
          </div>
          <div class="modal-footer">
            <div class="btn-group" rol="group">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar <i class="fas fa-times"></i></button>
                <button type="button" class="btn btn-success" id="guardarUrl" data-idtesis="{{ $t->first()->id }}">Guardar <i class="fas fa-download"></i> </button>
            </div>
          </div>
        </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){



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


        $('#enlacegdrive').on('shown.bs.modal', function(e){
            $('#urldoc').focus().select();
        });

        $('#guardarUrl').click(function(){            
            var idt = $(this).data('idtesis');
            var url = $('#urldoc').val();
            var ge = $.post(
                        "{{ url('tesisGuardarUrl') }}",
                        {
                            idtesis:idt,
                            urldoc:url,
                            _token:$('meta[name="csrf-token"]').attr("content")

                        }
                );
             if(url !== ''){
                $('#abrirUrl').removeClass('disabled');
             }else{
                $('#abrirUrl').addClass('disabled');
             }
            $('#enlacegdrive').modal('hide');//modal para captura del enlace a Google Drive
        });
       
        $('.email').click(function(){
            var email = $(this).data('email');
            var temp = $("<input>")
            $("body").append(temp);
            temp.val(email).select();
            document.execCommand("copy");
            temp.remove();
            alert('La dirección de correo electrónico ha sido copiada al portapapeles,\nvaya a su gestor de correo electrónico y péguela en la dirección\ndel destinatario');
        });    

    });
</script>
@endsection


{{-- https://drive.google.com/file/d/0B-XT6dL9f234cHhQdmNpamRDemJ0NWxCbWhteWhLY2FINmxj/view?usp=sharing --}}