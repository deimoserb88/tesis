@extends('layouts.academico')

@section('estilos')
{{ Html::style('/public/assets/vendor/datatables/media/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')

<div class="container">

	<form action="{{ url('tesisGuardar') }}" class="form-horizontal" method="post">
		{{ csrf_field() }}
	<div class="panel panel-primary" style="width: 75%;margin: 0 auto;">
		<div class="panel-heading"><h4 class="panel-title">Registrar nueva tesis</h4></div>
		<div class="panel-body">
			<div class="form-group">
				{{ Form::label('idprograma','Carrera',['class'=>'col-sm-2']) }}
				<div class="col-sm-10">
					<select name="idprograma" class="form-control" required="required">
						@foreach ($progs as $prog)
							<option value="{{ $prog->id }}">{{ $prog->programa }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('titulo','Título',['class'=>'col-sm-2']) }}
				<div class="col-sm-10">
					{{ Form::text('titulo','',['class'=>'form-control','placeholder'=>'Título propuesto para la tesis','required'=>'required']) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('desc','Descripción',['class'=>'col-sm-2']) }}
				<div class="col-sm-10">
					{{ Form::textarea('desc','',['class'=>'form-control','placeholder'=>'Descripción general de la tesis','required'=>'required','rows'=>'5']) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('gen','Generación',['class'=>'col-sm-2']) }}
				<div class="col-sm-2">
					{{ Form::number('gen',date('Y'),['class'=>'form-control','required'=>'required','min'=>date('Y')-1]) }}
				</div>
				<div class="col-sm-8"></div>
			</div>
			<div class="form-group">
				{{ Form::label('tesistas','Número de tesistas',['class'=>'col-sm-2']) }}
				<div class="col-sm-1">
					{{ Form::select('tesistas',['1'=>'1','2'=>'2','3'=>'3',],'1',['class'=>'form-control']) }}
				</div>
				<div class="col-sm-9"></div>
			</div>


		</div>
		<div class="panel-footer text-right">
			<div class="btn-group" rol="group">
				<button type="button" class="btn btn-danger cancelar">Cancelar<i class="fa fa-btn fa-times"></i></button>
				<button type="submit" class="btn btn-success">Guardar <i class="fa fa-btn fa-download"></i></button>
			</div>
		</div>

	</div>
	</form>


</div>

@endsection

@section('scripts')

{{ Html::script('/public/assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}
{{ Html::script('/public/assets/vendor/datatables/media/js/dataTables.bootstrap.min.js') }}

<script type="text/javascript">

    $(document).ready(function() {

    	$('.cancelar').click(function(){
    		window.location.href='{{ url('/tesis') }}';
    	});

    });

</script>

@endsection
