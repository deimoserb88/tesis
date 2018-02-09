@extends('layouts.academico')

@section('content')

<div class="container">
  
	<form action="{{ url('tesisGuardarPdf') }}" class="form-horizontal" method="post"  enctype="multipart/form-data">
		{{ csrf_field() }}
		<input type="hidden" name="idtesis" value="{{ $t->first()->id }}">
		<div class="panel panel-primary" style="width: 75%;margin: 0 auto;">
			<div class="panel-heading"><h4 class="panel-title">Subir teisis en formato PDF</h4></div>
			<div class="panel-body">
				<div class="alert {{ count($errors)>0 ? ' alert-danger' : ' alert-info' }}">
	                El documento debe ser PDF y no debe tener un tamaño mayor a 10 MB
	            </div>
	            <div class="form-group{{ count($errors) > 0 ? ' has-error' : '' }}">
	            	<div class="col-md-12">
	            	<input type="file" class="file" name="pdf" id="pdf" accept=".pdf,.txt" required="required">
    	            @if ($errors->has('muygrande'))
                        <span class="help-block">
                            <strong>Su archiovo es my grande: {{ number_format($errors->first() / 1000000,1) }} MB</strong>
                        </span>
                    @elseif ($errors->has('nombreinvalido'))
                        <span class="help-block">
                            <strong>El archivo debe ser tipo PDF, archivo: {{ $errors->first() }}</strong>
                        </span>                    
                    @endif
                    </div>
	            </div>
	        </div>
			<div class="panel-footer text-right">
				<div class="btn-group" rol="group">
					<button type="button" class="btn btn-danger cancelar">Cancelar<i class="fa fa-btn fa-times"></i></button>			
					<button type="submit" class="btn btn-success">Guardar <i class="fa fa-btn fa-upload"></i></button>			
				</div>
			</div>
		</div>
	</form>


</div>

@endsection

@section('scripts')

<script type="text/javascript">

    $(document).ready(function() {

    	$('.cancelar').click(function(){
    		window.location.href='{{ url('/tesistaHome') }}';
    	});

    });

</script>

@endsection
