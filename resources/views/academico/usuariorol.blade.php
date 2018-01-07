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
                		<div class="col-md-12">
                			<h4 style="display: inline;">Actividad del usuario: <span class="label label-warning">{{ $u[0]->nombre }}, {{ $u[0]->nocontrol }}</span> </h4>
                		</div>
                	</div>
                </div>
                <div class="panel-body">
                    <div class="alert alert-info">
                    <div class="row">
                        <div class="col-sm-8">Roles:</div>
                        <div class="col-sm-4 text-right">
                            @if(Auth::user()->priv <= 2)
                            <button class="btn btn-default btn-xs"  data-toggle="modal" data-target="#asignarrol">Asignar <i class="fa fa-btn fa-plus"></i> </button>
                            @endif
                        </div>
                    </div>
                    </div>
                    <table class="table table-striped" id="tua">
                        <thead>
                            <tr>
                                <th>Rol</th>
                                <th>Programa</th>
                                <th class="text-center"><i class="fa fa-cog" aria-hidden="true"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($r as $rol)
                                <tr>
                                    <td>{{ tesis\Rol::rol($rol->rol) }}</td>
                                    <td>{{ $rol->programa }}</td>
                                    <td class="text-center">
                                        {{--Los que tiene privilegios 1 pueden eliminar cualquier rol, los demas (2 o 3) solo los roles de su programa--}}
                                        @if(Auth::user()->priv == 1 || (Auth::user()->priv <= 3 && $rol->idprograma == $p[0]->id))
                                            <div class="btn-group" rol="group">
                                                <a href="{{ url('quitarRol/'.$rol->id.'/'.$u[0]->id) }}" class="btn btn-danger btn-xs"><i class="far fa-trash-alt"></i></a>
                                            </div>
                                        @endif
                                   </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="panel-footer">
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para asignar rol a usuario --}}


<div class="modal fade" tabindex="-1" role="dialog" id="asignarrol">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('rolAsignar') }}" method="post" id="usuarioGuardar"  class="form-horizontal">
             {{ csrf_field() }}
             <input type="hidden" name="id" value="{{ $u[0]->id }}">
                <div class="modal-header">
                    <button type="button" class="close cancelar" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Asignar rol</h4>
                </div>
                <div class="modal-body">

                  <div class="form-group{{ isset($errores)?($errores->has('prog') ? ' has-error' : ''):'' }}">
                    <label for="password" class="col-sm-4 control-label">Programa</label>
                    <div class="col-sm-8">
                        <select name="prog" class="form-control" id="rprog" required="required">
                            @if(Auth::user()->priv == 1)
                                @foreach($p as $prog)
                                    <option value="{{ $prog->id }}">{{ $prog->programa }}</option>
                                @endforeach
                            @else
                                <option value="{{ $p[0]->id }}">{{ $p[0]->programa }}</option>
                            @endif
                        </select>
                    </div>
                  </div>
                  <div class="form-group{{ isset($errores)?($errores->has('rol') ? ' has-error' : ''):'' }}">
                    <label for="password" class="col-sm-4 control-label">Rol</label>
                    <div class="col-sm-8">
                        <select name="rol" class="form-control" id="rol" required="required">
                            @foreach(range($urol[0]['rol'] + 1,9) as $i)
                                <option value="{{ $i }}">{{ tesis\Rol::rol($i) }}</option>
                            @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="form-group hidden dgen {{ isset($errores)?($errores->has('gen') ? ' has-error' : ''):'' }}">
                    <label for="password" class="col-sm-4 control-label">Generación</label>
                    <div class="col-sm-8">
                        {{ Form::number('gen',date('Y')) }}
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


        $('[data-toggle="tooltip"]').tooltip();

        $(function(){ //con esto podemos llamar funciones al momento de que el docuemtno se carga
            $('#tprog').change();
        });

        $('#tprog,#gen').change(function(){
            var at = $.post(
                    "{{ url('getTesisId') }}",
                    {
                        gen:$('#gen').val(),
                        prog:$('#tprog').val(),
                        _token:$('meta[name="csrf-token"]').attr("content"),
                    }
                );
            at.done(function(resp){
                var r = '';
                resp.forEach(function(v){
                    r = '<option value="' + v.id + '">' + v.nom + '</option>'+"\n";
                    $('#idtesis').html(r);
                });
                if(r === ''){
                   $('#idtesis').html('<option disabled="disabled">No hay tesis para el programa y generación seleccionados</option>');
                }
            });
        });

        $('#rol').change(function(){
            var r = $(this);
            if(r.val() === '9'){
                $('.dgen').removeClass('hidden');
            }else{
                $('.dgen').addClass('hidden');
            }
        });


    });

</script>

@endsection
