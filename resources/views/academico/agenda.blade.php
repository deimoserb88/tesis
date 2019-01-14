@extends('layouts.academico',['rol'=>min(array_column($urol,'rol'))])

@section('estilos')
{{ Html::style('/public/assets/vendor/fullcalendar/dist/fullcalendar.min.css') }}
{{ Html::style('/public/assets/vendor/fullcalendar/dist/fullcalendar.print.min.css',['media'=>'print']) }}
{{ Html::style('/public/assets/vendor/datetimepicker/build/css/bootstrap-datetimepicker.css')}}
@endsection

@section('content')

<div class="container" id="app">	
	<div id="calendar"></div>
</div>

@include('academico.partials.nuevaactividad')
@include('academico.partials.editaractividad')
@include('academico.partials.detalleactividad')

@endsection

@section('scripts')

{{ Html::script('/public/assets/vendor/moment/moment.js')}}
{{ Html::script('/public/assets/vendor/fullcalendar/dist/fullcalendar.min.js')}}
{{ Html::script('/public/assets/vendor/fullcalendar/dist/locale/es.js')}}
{{ Html::script('/public/assets/vendor/datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}

<script>
	$(function(){
		
		var date = new Date();
       	var yyyy = date.getFullYear().toString();
       	var mm = (date.getMonth()+1).toString().length == 1 ? "0"+(date.getMonth()+1).toString() : (date.getMonth()+1).toString();
       	var dd  = (date.getDate()).toString().length == 1 ? "0"+(date.getDate()).toString() : (date.getDate()).toString();	

       	var idprogs = String({{ implode(",", $idprogs) }}).split(",");//traduccion de las ids de los programas en los que colabora de php a javascript
       	//console.log(idprogs);	
       	moment.locale('es');

		$('#calendar').fullCalendar({
    		header: {
        				left: 'prev,next today',
				        center: 'title',
				        right: 'month,basicWeek,basicDay'
				    },
			defaultDate: yyyy+"-"+mm+"-"+dd,
		    navLinks: true, // can click day/week names to navigate views		    
			editable: {{ $es_psi }},
			eventLimit: true, // allow "more" link when too many events
			selectable: {{ $es_psi }},
			selectHelper: true,
			select: function(start, end) {				
						$('#nueva-actividad #start').val(moment(start).format('YYYY-MM-DD HH:mm'));
						$('#nueva-actividad #end').val(moment(end).format('YYYY-MM-DD HH:mm'));
						$('#nueva-actividad').modal('show');
			},
			eventRender: function(event, element) {
				element.bind('dblclick', function() {
				//se evalua que el academico sea PSI y que el evento pertenezca a un programa en el que el academico lo sea
					if({{ $es_psi }} && idprogs.indexOf(event.idprograma) >= 0 ){
						$('#edita-actividad #id').val(event.id);
						$('#edita-actividad #idtesis').val(event.idtesis+":"+event.idprograma);					
						$('#edita-actividad #actividad').val(event.actividad);
						$('#edita-actividad #color').val(event.color);
						$('#edita-actividad').modal('show');
					}else{
						//crear un modal para ver los detalles de la actividad,  cuando no está habilitado para editarlos
						$('#detalle-actividad #dactividad').text(event.actividad);
						$('#detalle-actividad #dtesis').text(event.title+":"+event.idprograma);					
						$('#detalle-actividad #dinicia').text(moment(event.start).format("dddd, DD/MM/YYYY HH:mm"));
						$('#detalle-actividad #dresp').text(event.responsable);
						$('#detalle-actividad').modal('show');
					}
				});
			},	
			eventDrop: function(event, delta, revertFunc) { // si changement de position
				edit(event);
			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur
				edit(event);
			},
			events: [
				<?php foreach($ams as $am): 
					$start = explode(" ", $am['inicio']);
					$end = explode(" ", $am['fin']);
					if($start[1] == '00:00:00'){
						$start = $start[0];
					}else{
						$start = $am['inicio'];
					}
					if($end[1] == '00:00:00'){
						$end = $end[0];
					}else{
						$end = $am['fin'];
					}
					//buscar el nombre de las tesis en el array tesis que corresponde a cada actividad mensual
					$nom = $t[array_search($am['idtesis'],array_column($t,'id'))]['nom'];

				?>
				{
					id: 		"{{ $am['id'] }}",
					actividad: 	"{{ $am['actividad'] }}",
					title: 		"{{ $nom }}",
					start: 		"{{ $start }}",
					end: 		"{{ $end }}",
					color: 		"{{ $am['color'] }}",
					idtesis: 	"{{ $am['idtesis'] }}",
					idprograma: "{{ $am['idprograma'] }}",
					responsable:"{{ $res[array_search($am['idusuario'],array_column($res,'id'))]['nombre'] }}"
				},
				<?php endforeach; ?>
			]					    
  		});
		
		$(".fc-prev-button, .fc-next-button").click(function(){			
				var mes = $('#calendar').fullCalendar('getDate').format('M');
				var anio = $('#calendar').fullCalendar('getDate').format('YYYY');
				var hoy = new Date();
				console.log(mes + " - " + (hoy.getMonth()+1) + " / " + anio + " - " + hoy.getFullYear());
				if(mes != (hoy.getMonth()+1) || anio != hoy.getFullYear()){
					var Eventos = [];					
					var url = "{{ url('obtenerActividades') }}" + "/" + mes + "/" + anio;
					axios.get(url).then(response => {						
						response.data.forEach(am => {
							Eventos.push({
								id: 		am.id,
								actividad: 	am.actividad,
								title: 		am.nom,
								start: 		am.inicio,
								end: 		am.fin,
								color: 		am.color,
								idtesis: 	am.idtesis,
								idprograma: am.idprograma,
								responsable:am.nombre								
							});
						});
						$('#calendar').fullCalendar('renderEvents',Eventos);					
					}).catch(function(error){
						console.log(error);
					});
				}
		});

		function edit(event){
			
			start = event.start.format('YYYY-MM-DD HH:mm');
			if(event.end){
				end = event.end.format('YYYY-MM-DD HH:mm');
			}else{
				end = start;
			}
			
			id =  event.id;
			
			var url = "{{ route('cambiarActividad') }}";

			axios.post(url, {id:id,start:start,end:end}).then(response => {
				toastr.success("La actividad se actualizó correctamente","Agenda");				
			}).catch(function(error){				
				toastr.error("La actividad no se pudo actualizar, inténtelo de nuevo: " + error);
			});
		}

		$('#qstart').datetimepicker();
		$('#qstart').data("DateTimePicker")
			.format("YYYY-MM-DD HH:mm")
			.sideBySide(true);
		$('#qend').datetimepicker();
		$('#qend').data("DateTimePicker")
			.format("YYYY-MM-DD HH:mm")
			.sideBySide(true);

	});
</script>

@endsection
