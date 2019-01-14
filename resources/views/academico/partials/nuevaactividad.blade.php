<div class="modal fade" id="nueva-actividad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	<form class="form-horizontal" method="POST" action="{{ route('actividadGuardar') }}">
		{{ csrf_field() }}
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">Nueva actividad</h4>
	  </div>
	  <div class="modal-body">
		
		  <div class="form-group">
			<label for="actividad" class="col-sm-2 control-label">Actividad</label>
			<div class="col-sm-10">
			  <input type="text" name="actividad" class="form-control" id="actividad" placeholder="Breve descripción de la actividad">
			</div>
		  </div>
		  <div class="form-group">
			<label for="color" class="col-sm-2 control-label">Tesis</label>
			<div class="col-sm-10">
			  	<select name="idtesis" class="form-control" id="idtesis">
					<option value="">--</option>
					@if(count($t) > 0 )
						@foreach($t as $tesis)
							@if(in_array($tesis['idprograma'], $idprogs)) {{-- Solo se muestran las tesis de sus proigramas donde tenga rol de PSI --}}
								<option value="{{ $tesis['id'] }}:{{ $tesis['idprograma'] }}">{{ $tesis['nom'] }}</option>
							@endif
						@endforeach
					@else
						<option value="" disabled="disabled">-- No tiene tesis disponibles --</option>
					@endif					
				</select>
			</div>
		  </div>
		  <div class="form-group">
			<label for="color" class="col-sm-2 control-label">Color</label>
			<div class="col-sm-10">
			  	<select name="color" class="form-control" id="color">
					<option value="">--</option>
				  	<option style="color:#0071c5;" value="#0071c5">&#9724; Azul oscuro</option>
					<option style="color:#008000;" value="#008000">&#9724; Verde</option>
					<option style="color:#FFD700;" value="#FFD700">&#9724; Amarillo</option>
					<option style="color:#FF0000;" value="#FF0000">&#9724; Rojo</option>
					<option style="color:#000;" value="#000">&#9724; Negro</option>
				</select>
			</div>
		  </div>
		  <div class="form-group">
			<label for="start" class="col-sm-2 control-label">Inicia</label>
			<div class="col-sm-10">
				<div class="input-group date" id="qstart">
                	<input type='text' class="form-control" name="start" id="start" >
                	<span class="input-group-addon">
                    	<span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
			  {{-- <input type="text" name="start" class="form-control" id="start" readonly> --}}
			</div>
		  </div>
		  <div class="form-group">
			<label for="end" class="col-sm-2 control-label">Termina</label>
			<div class="col-sm-10">
				<div class="input-group date" id="qend">
                	<input type='text' class="form-control" name="end" id="end">
                	<span class="input-group-addon">
                    	<span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>				
			  {{-- <input type="text" name="end" class="form-control" id="end" readonly>--}}
			</div>
		  </div>
		
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
		<button type="submit" class="btn btn-success">Guardar</button>
	  </div>
	</form>
	</div>
  </div>
</div>