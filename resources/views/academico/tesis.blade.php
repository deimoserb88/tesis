@extends('layouts.academico')

@section('estilos')
{{ Html::style('/public/assets/vendor/datatables/media/css/dataTables.bootstrap.min.css') }}  
@endsection

@section('content')

<div class="container">
    <h1>Tesis</h1>
</div>

@endsection

@section('scripts')

{{ Html::script('/public/assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}
{{ Html::script('/public/assets/vendor/datatables/media/js/dataTables.bootstrap.min.js') }} 

<script type="text/javascript">

    $(document).ready(function() {


    });

</script>

@endsection
