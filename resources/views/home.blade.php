@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Bienvenido</h3>
                </div>
                <div class="panel-body">

                    @if($tu == 'a' || ($tu == 't' && count($t) > 0))
                        Estimado usuario 
                        @if($tu == 'a')
                            académico,
                        @else
                            tesista,
                        @endif
                        para poder hacer uso del sistema de gestion de Tesis es necesario
                        que tenga un Rol asignado el cual debe solicitar al
                        @if($tu == 'a')
                            coordinador o al presidente de academia de la carrera en la que participa como docente asesor, coasesor,
                            revisor o titular de Seminario de Investigación. En caso de ser coordinador de carrera o presidente de academia
                            solicite su Rol al dirctor o al coordinador académico de la facultad.                        
                        @else
                            profesor titular de Seminario de Investigación.
                        @endif                    
                        <br><br>
                        <table class="table table-hover">                        
                        @foreach($u as $usuario)
                            <tr>
                                <td>{{ $usuario->nombre }}</td>
                                <td>{{ tesis\Rol::rol($usuario->rol) }}</td>
                                @if($usuario->rol<=2)
                                    <td> - </td>
                                @else
                                    <td>{{ $usuario->abrev }}</td>
                                @endif
                                <td>
                                    <div class="btn-group" rol="group">
                                        <a href="#" class="btn btn-warning btn-sm em" data-usuario="{{ $usuario->nombre }}" data-idusuario="{{ $usuario->id }}" data-toggle="modal" data-target="#emensaje"><i class="fa fa-commenting" ></i></a>
                                        <a href="mailto:{{ $usuario->email }}" class="btn btn-warning btn-sm"><i class="fa fa-envelope"></i></a>
                                    </div>
                                </td>
                            </tr>                            
                        @endforeach
                        </table>
                    @else
                        Estimado tesista, por favor selecciona la carrera de la que egresas y la generación:
                        <hr>
                        {{ Form::open(['url'=>'tesistaProGen','method'=>'post','class'=>'form-horizontal tesistaProGen']) }}
                        {{ Form::hidden('idusuario',Auth::user()->id) }}
                        <div class="form-group">
                            <label for="programa" class="col-sm-2 control-label">Carrera:</label>
                            <div class="col-sm-10">
                                <select name="idprograma" id="idprograma" class="form-control"  required="required">
                                    <option value="" selected="selected" disabled="disabled">--</option>
                                    @foreach($p as $prog)
                                        <option value="{{ $prog->id}}">{{ $prog->programa }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gen" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-2">
                                <input type="number" min="{{ date('Y') }}" max="{{ date("Y") + 5 }}" value="{{ date('Y') }}" class="form-control" name="gen" id="gen" required="required">
                            </div>
                            <div class="col-sm-8"></div>
                        </div>                        
                        {{ Form::close() }}
                    @endif
                </div>
                <div class="panel-footer text-right">
                    <div class="btn-group" rol="group">
                        @if($tu == 't' && count($t) == 0)
                            <a href="#" type="button" class="btn btn-success guardar">Guardar <i class="icon-guardar"></i></a>
                        @endif
                        <a href="{{ route('logout') }}" class="btn btn-warning" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        Salir <i class="icon-logout"></i></a>
                    </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>            
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
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar <i class="fa fa-close"></i></button>
                <button type="button" class="btn btn-success" id="idusuario" value="">Enviar <i class="fa fa-send-o"></i> </button>
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

        $('.guardar').click(function(){
            $('.tesistaProGen').trigger('submit');
        });


    });
                

            
</script>

@endsection