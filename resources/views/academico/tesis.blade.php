@extends('layouts.academico')

@section('estilos')
{{ Html::style('/public/assets/vendor/datatables/media/css/dataTables.bootstrap.min.css') }}  
@endsection

@section('content')

<div class="container">

    <div class="panel panel-default" style="margin: 0 auto 30px;">
		<div class="panel-heading">
			<div class="row">
			<div class="col-sm-10"><h3 class="panel-title">Tesis</h3></div>
			<div class="col-sm-2">
			    @if(in_array(6, array_column($urol, 'rol')))
			    	<a href="{{ url('/tesisNueva') }}" class="btn btn-sm btn-default">Tesis nueva <i class="fa fa-btn fa-file-text-o"></i> </a>    	
			    @endif				
			</div>
			</div>
		</div>
		<div class="panel-body">

			@if(count($tesisA)>0)
			<div class="alert alert-info alert-dismissible">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  Tesis de las carreras en las que tiene rol de gestión (Director, Coordinador académica, jefe de carrera, presidente de academia, titular de seminario de investigación)
			</div>
			<table class="table table-striped table-hover tesis" id="tesisA" style="width: 100%;">
				<thead>
					<tr>
						<th>ID</th>
						<th>Titulo</th>
						<th>Carr.</th>					
						<th>Gen.</th>
						<th>Estado</th>
						<th class="text-center" ><i class="fas fa-cog"></i></th>
					</tr>
				</thead>
				<tbody>
					@foreach($tesisA as $t)
						<tr>
							<td>{{ $t->id }}</td>
							<td>
								<button class="btn btn-link dt" data-idtesis="{{ $t->id }}" data-titulo="{{ $t->nom }}" data-toggle="modal" data-target="#datostesis">
									{{ $t->nom }}
								</button>
							</td>	
							<td>{{ $t->abrev }}</td>	
							<td>{{ $t->gen }}</td>
							<td>{{ tesis\Tesis::tesisEstado($t->estado) }}</td>
							<td  class="text-right">								
								<div class="btn-group" rol="group">
								@if($t->estado == 1 && in_array(4, array_column($urol, 'rol')))
									<a class="btn btn-success btn-sm aprobar" href="{{ url('tesisAprobar/'.$t->id) }}">Aprobar<i class="fas fa-check"></i></a>
								@endif
								@if(($t->estado>=2&&$t->estado<=3)&&(Auth::user()->priv>=2&&Auth::user()->priv<=3))
								{{-- Boton para asignar tesistas --}}	
									<a class="btn btn-primary btn-sm" href="{{ url('tesisTesista/'.$t->id) }}" "><i class="fa fa-btn fa-user"></i></a>
								@endif
								@if(!is_null($t->pdf))
								<button class="btn btn-primary btn-sm disabled" type="button"><img src="{{ url('/public/images/pdfS.png') }}"></button>
								</div>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<hr>
			@endif

			<div class="alert alert-info alert-dismissible">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  Tesis en las que participa como asesor, coasesor o revisor
			</div>
			<table class="table table-striped table-hover tesis" id="tesisP" style="width: 100%;">
				<thead>
					<tr>
						<th>ID</th>
						<th>Titulo</th>
						<th>Carr.</th>					
						<th>Gen.</th>
						<th>Estado</th>
						<th class="text-center"><i class="fas fa-cog"></i></th>
					</tr>
				</thead>
				<tbody>
					@foreach($tesisP as $t)
						<tr>
							<td>{{ $t->id }}</td>
							<td>
								<a href="#" class="dt" data-idtesis="{{ $t->id }}" data-titulo="{{ $t->nom }}" data-toggle="modal" data-target="#datostesis">
								{{ $t->nom }}
								</a> | {{ tesis\Rol::rol($t->rol) }}
							</td>	
							<td>{{ $t->abrev }}</td>	
							<td>{{ $t->gen }}</td>
							<td>{{ tesis\Tesis::tesisEstado($t->estado) }}</td>
							<td class="text-right">	
								<div class="btn-group" rol="group">							
								@if($t->rol == 6)
									<button class="btn btn-primary btn-sm"><i class="fa fa-btn fa-pencil"></i></button>
								@endif		
									<button class="btn btn-primary btn-sm disabled"><i class="fa fa-btn fa-file-pdf-o"></i></button>
								</div>
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

<div class="modal fade" tabindex="-1" role="dialog" id="datostesis">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Tesis: <span class="bg-warning" id="ttitulo">***</span></h4>
      </div>
      <div class="modal-body">
		<div class="alert alert-info detalles-tesis">
			<div class="row"><div class="col-sm-2">Descripción</div><div class="col-sm-10" id="tdesc"></div></div>
			<div class="row"><div class="col-sm-2">Tesistas</div><div class="col-sm-10" id="ttesistas"></div></div>
			<div class="row"><div class="col-sm-2">Asesor</div><div class="col-sm-10" id="tasesor"></div></div>
			<div class="row"><div class="col-sm-2">Coasesor</div><div class="col-sm-10" id="tcoasesor"></div></div>
			<div class="row"><div class="col-sm-2">Revisores</div><div class="col-sm-10" id="trevisores"></div></div>
		</div>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
    			
    			$('#ttitulo').text(dt.data('titulo'));
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

        $('.tesis').DataTable({
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
