@extends('layouts.academico',['rol'=>min(Request::session()->get('rol'))])

@section('content')

<div class="container">
  
	
		<div class="panel panel-default" style="width: 75%;margin: 0 auto;">
			<div class="panel-heading"><h4 class="panel-title">Subir teisis en formato PDF</h4></div>
			<div class="panel-body" style="height:500px;">
                <object style="width:100%;height:100%" data="{{ url('storage/app/public/tesis')."/".$ruta->first()->pdf }}" type="application/pdf">
                    <embed src="{{ url('storage/app/public/tesis')."/".$ruta->first()->pdf }}" type="application/pdf" />
                </object>
	        </div>
			<div class="panel-footer text-right">				
				<button type="button" class="btn btn-default volver"><i class="fas fa-reply"></i> Voler</i></button>				
			</div>
		</div>



</div>

@endsection

@section('scripts')

<script type="text/javascript">

    $(document).ready(function() {

    	$('.volver').click(function(){
    		history.back();
    	{{-- url('/academicoHome') --}}
    	});

    });

</script>

@endsection
