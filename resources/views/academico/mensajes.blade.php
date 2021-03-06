@extends('layouts.academico',['rol'=>min(array_column(Request::session()->get('rol'),'rol'))])

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
                            <h4 class="panel-title">Mensajes</h4>
                        </div>
                        <div class="col-md-3">
                            <select name="fmensajes" id="fmensajes" class="form-control">
                                <option value="2" {{ $fmensajes==2?'selected=selected':'' }}>Todos</option>
                                <option value="1" {{ $fmensajes==1?'selected=selected':'' }}>Leídos</option>
                                <option value="0" {{ $fmensajes==0?'selected=selected':'' }}>No leídos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover" id="tmensajes">
                        <thead>
                            <tr>
                                <th><i class="far fa-envelope"></i></th>
                                <th>De</th>
                                <th>Mensaje...</th>
                                <th class="text-center"><i class="fas fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($m as $mensaje)
                            <tr>
                                <td><button type="button" class="btn btn-link vm" data-idmensaje="{{ $mensaje->id }}" data-toggle="modal" data-target="#vermensaje"><i class="far s{{ $mensaje->id }} {{ $mensaje->leido==0?'fa-envelope':'fa-envelope-open' }} "></i></button></td>
                                <td>{{ $mensaje->nombre }}</td>
                                <td>
                                    <button type="button" class="btn btn-link vm" data-idmensaje="{{ $mensaje->id }}"  data-toggle="modal" data-target="#vermensaje">
                                    @if(strlen($mensaje->mensaje) < 20)
                                        {{ $mensaje->mensaje }}
                                    @else
                                        {{ substr($mensaje->mensaje,0,20) }} ...
                                    @endif
                                    </button>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" rol="group">
                                        <a class="btn btn-primary btn-sm vm" data-idmensaje="{{ $mensaje->id }}"  data-toggle="modal" data-target="#vermensaje" href="#" ><i class="fa fa-eye"></i></a>
                                        <a class="btn btn-warning btn-sm em" href="#" data-toggle="modal" data-target="#emensaje" data-usuario="{{ $mensaje->nombre }}" data-idusuario="{{ $mensaje->idusuario_de }}"><i class="fas fa-reply"></i></a>
                                        <a class="btn btn-danger btn-sm borrar-mensaje" data-idmensaje="{{ $mensaje->id }}" href="#"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="vermensaje">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title mensaje-de">...</h4>
      </div>
      <div class="modal-body">
        <p class="texto-mensaje">...</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-check"></i></button>        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


@endsection

@section('scripts')

{{ Html::script('/public/assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}
{{ Html::script('/public/assets/vendor/datatables/media/js/dataTables.bootstrap.min.js') }} 

<script  type="text/javascript">

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

        $(".borrar-mensaje").click(function(){
            var mb = $(this);
            if(confirm("¿Está seguro de boorar el mensaje seleccionado? ")){
                window.location.href = '{{ url("mensajeBorrar") }}/' + mb.data("idmensaje");
            }
        });

        $('#tmensajes').DataTable({
            "scrollY": 480,
            "scrollCollapse": true,
            "paging": true,
            "info": false,
            "language": {
                "search": "Filtrar:",
                "zeroRecords": "No hay mensajes",
                "paginate" : {                    
                    "previous": '<i class="fas fa-angle-left"></i>',
                    "next": '<i class="fas fa-angle-right"></i>',
                    "first": '<i class="fas angle-double-left"></i>',
                    "last": '<i class="fas angle-double-right"></i>',
                },
                "lengthMenu": "Mostrar _MENU_ mensajes",
            },
            "select": true,
            "emptyTable" : "No hay mensajes",
            "columnDefs": [
                { "orderable": false, "targets": [0,3] }
            ],             
        });
    });

    $('#fmensajes').change(function(){
        var fm = $(this);
        window.location.href = "{{ url('/mensajes/'.Auth::user()->id) }}/" + fm.val();
    });

    $('.vm').click(function(){
        var vm = $(this);
        var m = $.post(
                        "{{ url('leerMensaje') }}",
                        {
                            idmensaje:vm.data("idmensaje"),                            
                            _token:$('meta[name="csrf-token"]').attr("content")
                        }
                    );
            m.done(function(resp){
               $('.mensaje-de').html(resp[0].nombre + " | <small>" + resp[0].created_at + "</small>");
               $('.texto-mensaje').html(resp[0].mensaje);
               $('.s' + vm.data("idmensaje")).removeClass('fa-envelope');
               $('.s' + vm.data("idmensaje")).addClass('fa-envelope-open');

            });
            m.always(function(resp){
                //console.log(resp);                
            });            
    });


</script>
@endsection