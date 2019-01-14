@extends('layouts.academico',['rol'=>min(array_column($urol,'rol'))])

@section('content')

<div class="container" id="app">	
	<div class="panel panel-default" style="margin: 0 auto 30px;">
		<div class="panel-heading">
			<div class="row">
			<div class="col-sm-1"><h3 class="panel-title">Tesis</h3></div>			
			<div class="col-sm-9">
				<h4>{{ $t->nom }}</h4>
			</div>
			<div class="col-sm-2">				
			    @if(in_array(5, array_column($urol, 'rol')))
			    	<a href="#" class="btn btn-sm btn-warning" @click.prevent="calificaTesis({{ $t->id }})"><i class="far fa-edit"></i> Calificar</a>
			    @endif
			</div>
			</div>
		</div>
		<div class="panel-body">

			<div class="row">
				@php
					$ev = 0;
				@endphp
				@foreach($ct as $cal)

					<div class="col-sm-2">
						<div class="panel panel-primary">
  							<div class="panel-heading text-center"><h5>{{ $cal->eval }}a Par</h5></div>
							<div class="panel-body text-center">
								<div v-show="!editar{{ $cal->eval }}">
									<h2 style="display: inline;" v-text="cal{{ $cal->eval }}"></h2>
								</div>		
								<div v-show="editar{{ $cal->eval }}">
									<div class="input-group input-group-lg">										
										<input type="number" size="3" maxlength="3" max="10" min="0" class="form-control" name="cal{{ $cal->eval }}" v-model="cal{{ $cal->eval }}">
										<span class="input-group-btn">
											<button type="button" class="btn btn-success" @click="guardaCal({{ $cal }})">
												<i class="fas fa-check"></i>
											</button>
										</span>
									</div>
								</div>						
							</div>
							@if(in_array(5, array_column($urol, 'rol')))
							<div class="panel-footer text-right">
								<div class="btn-group" rol="group">
									<button class="btn btn-link btn-xs" @click="editaCal({{ $cal }})">
										<i class="fas fa-pencil-alt"></i>
									</button>
									<button class="btn btn-link btn-xs" v-if="eval == {{ $cal->eval + 1 }}" @click="eliminaCal({{ $cal }})">
										<i class="far fa-trash-alt"></i>
									</button>
								</div>
							</div>
							@endif
						</div>
					</div>	
					@php
						$ev = $cal->eval;
					@endphp
				@endforeach
				
				<div class="col-sm-3 pull-right">
					<div class="panel panel-info">
							<div class="panel-heading text-center"><h4>Promedio</h4></div>
						<div class="panel-body text-center">
							{{-- <h2>{{ number_format($p,1) }}</h2> --}}
							<h2 v-text="prom"></h2>
					</div>
				</div>				
			</div>
		</div>
	</div>
	<div class="panel-footer text-muted">-FIE-</div>

@include('academico.partials.tcalifica')

</div>
</div>
@endsection

@section('scripts')

<script>
	$(document).ready(function(){
		tesisCalificar.getCalificaciones({{ $t->id }});
	})
</script>

@endsection