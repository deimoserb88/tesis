@extends('layouts.academico')

@section('estilos')
{{ Html::style('/public/assets/vendor/datatables/media/css/dataTables.bootstrap.min.css') }}  
@endsection

@section('content')

<div class="container">

    <div class="panel panel-default" style="width: 75%;margin: 0 auto;">
		<div class="panel-heading">
			<div class="row">
			<div class="col-sm-10"><h3 class="panel-title">Tesis</h3></div>
			<div class="col-sm-2">
			    @if(Auth::user()->priv == 4)
			    	<a href="{{ url('/tesisNueva') }}" class="btn btn-sm btn-default">Nueva tesis <i class="fa fa-btn fa-file-text-o"></i> </a>    	
			    @endif				
			</div>
			</div>
		</div>
		<div class="panel-body">
			<table class="table table-striped table-hover" id="tesis" style="width: 100%;">
				<thead>
					<tr>
						<th>ID</th>
						<th>Titulo</th>
						<th>Carr.</th>					
						<th>Gen.</th>
						<th>Estado</th>
						<th class="text-center"><i class="fa fa-gear"></i></th>
					</tr>
				</thead>
				<tbody>
					@foreach($tesis as $t)
						<tr>
							<td>{{ $t->id }}</td>
							<td>
								<a href="#" class="dt" data-idtesis="{{ $t->id }}">
								{{ $t->nom }}
								</a>
							</td>	
							<td>{{ $t->abrev }}</td>	
							<td>{{ $t->gen }}</td>
							<td>{{ tesis\Tesis::tesisEstado($t->estado) }}</td>
							<td>								
								@if($t->estado == 1 && in_array(4, array_column($urol, 'rol')))
									<button class="btn btn-success btn-xs">Aprobar<i class="fa fa-btn fa-check"></i></button>
								@endif
								@if($t->estado>=2&&$t->estado<=3)
								{{-- Botn para asignar tesistas --}}	
								<div class="btn-group" rol="group">
									<button class="btn btn-primary btn-xs" type="button"><i class="fa fa-btn fa-user-plus"></i></button>
									<button class="btn btn-danger btn-xs" type="button"><i class="fa fa-btn fa-user-times"></i></button>
								</div>
								@endif
		
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div class="panel-footer">
			Detalles de la tesis. De clic en el título de la tesis para ver sus detalles
			<div class="alert alert-defalt detalles-tesis" style="display: none;">
				<div class="row"><div class="col-sm-2">Descripción</div><div class="col-sm-10" id="tdesc"></div></div>
				<div class="row"><div class="col-sm-2">Tesistas</div><div class="col-sm-10" id="ttesistas"></div></div>
				<div class="row"><div class="col-sm-2">Asesor</div><div class="col-sm-10" id="tasesor"></div></div>
				<div class="row"><div class="col-sm-2">Coasesor</div><div class="col-sm-10" id="tcoasesor"></div></div>
				<div class="row"><div class="col-sm-2">Revisores</div><div class="col-sm-10" id="trevisores"></div></div>
			</div>
		</div>
	</div>
</div>


@endsection

@section('scripts')

{{ Html::script('/public/assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}
{{ Html::script('/public/assets/vendor/datatables/media/js/dataTables.bootstrap.min.js') }} 

<script type="text/javascript">

    $(document).ready(function() {

    	$('.dt').click(function(){
    		var dt = $(this);
    		var t = $.post(
	    				"{{ url('getTesisDetalle') }}",
	    				{
	    					idtesis:dt.data("idtesis"),
	    					_token:$('meta[name="csrf-token"]').attr("content")
	    				}
    				);
    		var tesis = asesores = coasesores = revisores = tsts = '';
    		t.done(function(resp){
    			
    			$('#tdesc').html('<b>'+resp.tesis[0].desc+'</b>');
    			
    			resp.docentes.forEach(function(v){
    				switch(v.rol){
    					case 6: asesores = v.nombre + ', ' + asesores;break;
    					case 7: coasesores = v.nombre + ', ' + coasesores;break;
    					case 8: revisores = v.nombre + ', ' + revisores;break;
    				}
    			});
    			$('#tasesor').html('<b>'+asesores+'</b>');
    			$('#tcoasesor').html(coasesores.length>0?'<b>'+coasesores+'</b>':'<em class="text-muted">Sin coasesor</em>');
    			$('#trevisores').html(revisores.length>0?'<b>'+revisores+'</b>':'<em class="text-muted">Revisores no definidos</em>');
    			
    			resp.tesistas.forEach(function(v){
    				tsts = v.nombre + ', ' + tsts;
    			});
    			$('#ttesistas').html(tsts.length>0?'<b>'+tsts+'</b>':'<em class="text-muted">Ningún tesista asignado</em>');

    		});
    		$('.detalles-tesis').show();
    	});

        $('#tesis').DataTable({
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
                { "orderable": false, "targets": 5 }
            ],             
        });


    });

</script>

@endsection
