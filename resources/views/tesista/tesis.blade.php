@extends('layouts.tesista')

@section('estilos')
{{ Html::style('/public/assets/vendor/datatables/media/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')

<div class="container">

    <div class="panel panel-default" style="margin: 0 auto 30px;">
		<div class="panel-heading">
			<div class="row">
			<div class="col-sm-1"><h3 class="panel-title">Tesis</h3></div>			
			<div class="col-sm-9">
				Generación:
				<div class="btn-group">
                     <button type="button" class="btn btn-default">
                    @if($gen == '%%')
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
                            <li><a href="{{ url('/tesisTesistas/'.$gene->gen) }}">{{ $gene->gen }}</a></li>
                        @endforeach
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('/tesisTesistas') }}">Todas las generaciones</a></li>

                    </ul>
                </div>				
			</div>
			<div class="col-sm-2">

			</div>
			</div>
		</div>
		<div class="panel-body">

			@if(count($tesis)>0)

			<table class="table table-striped table-hover tesis" id="tesis" style="width: 100%;">
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
					@foreach($tesis as $t)
						<tr>
							<td>{{ $t->id }}</td>
							<td>
								<button class="btn btn-link dt" data-idtesis="{{ $t->id }}" data-titulo="{{ $t->nom }}" data-toggle="modal" data-target="#datostesis">
									@if(strlen($t->nom)>70)
										{{ substr($t->nom,0,70) }}...
									@else
										{{ $t->nom }}
									@endif
								</button>
								@if(count($miTesis)>0 && $t->id == $miTesis[0]->id)
									| <span class="label label-success">Tu tesis</span>

								@endif
							</td>
							<td>{{ $t->abrev }}</td>
							<td>{{ $t->gen }}</td>
							<td>{{ tesis\Tesis::tesisEstado($t->estado) }}</td>
							<td  class="text-right">
								<div class="btn-group" rol="group">			
									{{-- Boton para seleccionar la tesis de su interes --}}
									<a href="#" data-idtesis="{{ $t->id }}" class="btn btn-sm st {{ count($mts)>0 && in_array($t->id,array_column($mts,'idtesis')) ? 'btn-success':'btn-default'  }} ">
										<i class="fas fa-check"></i>
									</a>
									{{--  Boton para ver el documento pdf  --}}
									<a href="{{ url('tesisPdfVer').'/'.$t->id }}" class="btn btn-primary btn-sm ver-pdf {{ is_null($t->pdf) ? 'disabled' : '' }}"><img src="{{ url('/public/images/pdfS.png') }}"></a>
								</div>								
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<hr>
			@endif


		</div>
		<div class="panel-footer text-muted">-FIE-</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="datostesis">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Tesis: <span class="bg-warning" id="ttitulo"></span></h4>
      </div>
      <div class="modal-body">
		<div class="alert alert-info detalles-tesis">
			<div class="row"><div class="col-sm-2">Descripción</div><div class="col-sm-10" id="tdesc"></div></div>
			<div class="row"><div class="col-sm-2">Tesistas (<b><span id="tntesistas"></span></b>)</div><div class="col-sm-10" id="ttesistas"></div></div>
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


    $('.st').click(function(){
    	var st = $(this);
    	var t = $.post(
    			"{{ url('tesisSeleccionar') }}",
    			{
					idtesis:st.data("idtesis"),
					_token:$('meta[name="csrf-token"]').attr("content")    				
    			}
    		);
    		t.done(function(resp){
    			console.log(resp.r);
    			switch(Number(resp.r)){
    				case 1: st.removeClass('btn-default');
    						st.addClass('btn-success');
    						break;
    				case 2: st.removeClass('btn-success');
    						st.addClass('btn-default');
    						break;
    				case 3: alert('Sólo puedes seleccionar tres proyectos');
    			}
    		});

    });

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
    				console.log(v.nombre+" * " +v.rol);
    				switch(Number(v.rol)){

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
    			$('#tntesistas').html(resp.tesis[0].tesistas);
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
                { "orderable": false, "targets": 5 },
				{ "width": "50%", "targets": 1 }
            ],
        });

    });


</script>

@endsection
