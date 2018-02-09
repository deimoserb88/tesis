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
                			<h4 style="display: inline;">Tesis: <span class="label label-warning"> {{ $t[0]->nom }}, {{ $t[0]->abrev }}</span> </h4>
                		</div>
                	</div>
                </div>
                <div class="panel-body">
                    <div class="alert alert-info">
                    <div class="row">
                        <div class="col-sm-2">Tesistas:</div>
                        <div class="col-sm-10 text-right">
                            @if(count($ta) < $t[0]->tesistas)
                                @if(in_array(Auth::user()->priv,[2,3]) || array_intersect([3,4,5], array_column($urol,'rol')))
                                <button class="btn btn-default btn-xs"  data-toggle="modal" data-target="#asignatesis">Asignar <i class="fas fa-plus"></i> </button>
                                @endif
                            @else
                                Esta tesis ya tiene el número de tesistas asignados por el asesor: <span class="label label-danger"> {{ $t[0]->tesistas }}</span>
                            @endif
                        </div>
                    </div>
                    </div>
					<table class="table table-striped">{{--  id="tua" --}}
						<thead>
							<tr>
								<th>No. Cta.</th>
								<th>Nombre</th>
								<th class="text-center"><i class="fas fa-cog"></i></th>
							</tr>
						</thead>
						<tbody>
							@foreach($ta as $tesista)
								<tr>
									<td>{{ $tesista->nocontrol }}</td>
                                    <td>{{ $tesista->nombre }}</td>
									<td class="text-center">
                                        <a class="btn btn-danger btn-xs" href="{{ url('tesisRemoverTesista/'.$t[0]->id.'/'.$tesista->id) }}"><i class="far fa-trash-alt"></i> </a>                                        
                                    </td>
								</tr>
							@endforeach
						</tbody>
					</table>

                </div>
                <div class="panel-footer">
                    <div class="text-right">
                        <a href="/tesis" class="btn btn-default"><i class="fas fa-reply"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para asignar tesis a usuario --}}

<div class="modal fade" tabindex="-1" role="dialog" id="asignatesis">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cancelar" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Seleccione un tesista</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-hover" id="ttesistas">
                    <thead>
                        <tr>
                            <th>No. Cta.</th>
                            <th>Nombre</th>
                            <th class="text-center"><i class="fas fa-cog" aria-hidden="true"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tt as $tesista)
                            <tr>
                                <td>{{ $tesista->nocontrol }}</td>
                                <td>{{ $tesista->nombre }}</td>
                                <td class="text-center">
                                    {{-- class = at -> asignar tesista (a la tesis) --}}
                                    <button type="button" class="btn btn-success btn-xs at" data-idtesis="{{ $t[0]->id }}" data-idtesista="{{ $tesista->id }}"><i class="fas fa-check"></i> </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <div class="btn-group" rol="group">
                    <button type="button" class="btn btn-danger cancelar" data-dismiss="modal">Cancelar <i class="fas fa-times"></i></button>

                </div>
            </div>
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


        $('#ttesistas').DataTable({
            "scrollY": 300,
            "scrollCollapse": true,
            "paging": false,
            "info": false,
            "language": {
                "search": "Filtrar:",
                "zeroRecords": "No hay tesistas disponibles",
            },
            "select": true,
            "emptyTable" : "No hay datos para mostrar",
        });


        $('[data-toggle="tooltip"]').tooltip();


        $('.at').click(function(){
            var at = $(this);
            window.location.href = "{{ url('asignaTesista') }}/" + at.data('idtesis') + "/" + at.data('idtesista');
        });


    });

</script>

@endsection
