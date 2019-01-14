@extends('layouts.academico',['rol'=>min(array_column(session('rol'),'rol'))])

@section('estilos')
{{ Html::style('/public/assets/vendor/datatables/media/css/dataTables.bootstrap.min.css') }}
{{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css') }}
@endsection

@section('content')

<div class="container" id="app">

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
                            <li><a href="{{ url('/tesis/'.$gene->gen) }}">{{ $gene->gen }}</a></li>
                        @endforeach
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('/tesis') }}">Todas las generaciones</a></li>

                    </ul>
                </div>				
			</div>
			<div class="col-sm-2">
			    @if(in_array(6, array_column($urol, 'rol')))
			    	<a href="{{ url('/tesisNueva') }}" class="btn btn-sm btn-default">Tesis nueva <i class="far fa-file"></i> </a>
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
									@if(strlen($t->nom)>70)
										{{ substr($t->nom,0,70) }}...
									@else
										{{ $t->nom }}
									@endif
								</button>
							</td>
							<td>{{ $t->abrev }}</td>
							<td>{{ $t->gen }}</td>
							<td>
								@if($t->estado == 3)
									<a href="#" class="estado" data-pk="{{ $t->id }}">
										{{ tesis\Tesis::tesisEstado($t->estado) }}
									</a>
								@else
									{{ tesis\Tesis::tesisEstado($t->estado) }}
								@endif
							</td>
							<td  class="text-right">
								<div class="btn-group" rol="group">
								@if($t->estado == 1 && in_array(4, array_column($urol, 'rol')))
									<a class="btn btn-success btn-sm aprobar" href="{{ url('tesisAprobar/'.$t->id) }}">Aprobar <i class="fas fa-check"></i></a>
									<a class="btn btn-danger btn-sm eliminar" data-idtesis="{{ $t->id }}" href="#"><i class="fas fa-trash"></i></a>
								@endif
								@if(($t->estado>=2&&$t->estado<=3)&&((Auth::user()->priv>=2&&Auth::user()->priv<=3) || count(array_intersect([4,5], array_column($urol, 'rol')))>0))
								{{-- Boton para asignar tesistas --}}
									<a class="btn btn-primary btn-sm" href="{{ url('tesisTesista/'.$t->id) }}" ><i class="fas fa-users"></i></a>
								@endif	
								
									{{-- Boton para ver/asignar calificaciones El parametro d=>l es para definir el destino de retorno l->lista, v->asingar variables vue--}}
									<a class="btn btn-success btn-sm" href="{{ route('cal',['idtesis'=>$t->id,'d'=>'l']) }}" ><i class="fas fa-tasks"></i></a>

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
								@if(strlen($t->nom)>70) {{ substr($t->nom,0,70) }}... @else {{ $t->nom }} @endif
								</a> | {{ tesis\Rol::rol($t->rol) }}
							</td>
							<td>{{ $t->abrev }}</td>
							<td>{{ $t->gen }}</td>
							<td>
									{{ tesis\Tesis::tesisEstado($t->estado) }}
								
							</td>
							<td class="text-right">
								<div class="btn-group" rol="group">
								@if($t->rol == 6)
									<a href="{{ url('tesisEditar/'.$t->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a>
								@endif

								{{-- Boton para ver/asignar calificaciones El parametro d=>l es para definir el destino de retorno l->lista, v->asingar variables vue--}}
								<a class="btn btn-success btn-sm" href="{{ route('cal',['idtesis'=>$t->id,'d'=>'l']) }}" ><i class="fas fa-tasks"></i></a>								
								
								<a href="{{ url('tesisPdfVer').'/'.$t->id }}" class="btn btn-primary btn-sm ver-pdf {{ is_null($t->pdf) ? 'disabled' : '' }}"><img src="{{ url('/public/images/pdfS.png') }}"></a>
								<a href="{{ !is_null($t->urldoc)?$t->urldoc:'#' }}" target="_blank" class="btn btn-primary btn-sm ver-drive {{ is_null($t->urldoc) ? 'disabled' : '' }}"><i class="fab fa-google-drive"></i></a>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>

		</div>
		<div class="panel-footer text-muted">-FIE-
		</div>
	</div>
</div>

@include('academico.partials.detalletesis')

@endsection

@section('scripts')

{{ Html::script('/public/assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}
{{ Html::script('/public/assets/vendor/datatables/media/js/dataTables.bootstrap.min.js') }}
{{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js') }}

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
    			
    			//solo los jefes de carrera y los presidentes de academia poeden retiar a los coasesores/revisores
    			@if(count(array_intersect([3,4],array_column($urol,'rol')))>0)
    				var btnremoveOpen = ' <a href="#" class="text-danger" onclick="eliminarusuario(this)" ';
    				var btnremoveClose ='"><i class="fas fa-times-circle"></i></a>, ';
    			@else
    				var btnremoveOpen = '<span ';    			
    				var btnremoveClose ='"></span>,';    			
    			@endif

    			resp.docentes.forEach(function(v){

    				switch(Number(v.rol)){
    					case 6: asesores = v.nombre + ', ' + asesores;break;
    					case 7: coasesores = '' + v.nombre + btnremoveOpen + 'data-idtesis="' + resp.tesis[0].id + '" data-idusuario="' + v.id + btnremoveClose + coasesores;break;
    					case 8: revisores = v.nombre + btnremoveOpen + 'data-idtesis="' + resp.tesis[0].id + '" data-idusuario="' + v.id + btnremoveClose + revisores;break;
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


        $.fn.editable.defaults.mode = 'popup';
        $.fn.editable.defaults.placement = 'left';
        $.fn.editable.defaults.params = function(params){
            params._token = $('meta[name="csrf-token"]').attr("content");
            return params;
        };        

        $(".estado").editable({
            type: 'select',
            source: [	{'value':4,'text':'Concluida'},            			
            			{'value':6,'text':'Cacelada'},
            		],
            name: 'estado',
            emptytext: 'ND',
            pk: $(this).attr("data-pk"),
            url: '{{ route('tesisEstado') }}',
            title: 'Cambiar estado de la tesis'
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

		$('.eliminar').click(function(){
			if(confirm("¿Está seguro de eliminar esta tesis?")){
				window.location.href = "{{ url('tesisEliminar') }}" + "/" + $(this).data('idtesis');
			}
		});


    });

    function eliminarusuario(t){//coasesor/revisor
    			
    	if(confirm("¿Desea eleminar el coasesor/revisor?")){
    		var idusuario = t.getAttribute("data-idusuario");
    		var idtesis = t.getAttribute("data-idtesis");
    		$.ajax({
	    			method : "GET",
	    			url: "{{ url('tesisRemoverRevisor') }}",
	    			data: {
	    				idu : idusuario,
	    				idt : idtesis
	    			},
	    			error : function(xhr){
	    				alert("An error occured: " + xhr.status + " " + xhr.statusText);
	    			}

    			})
    			.done(function(r){
    				console.log(r);
    			});
    	}

    }

</script>

@endsection
