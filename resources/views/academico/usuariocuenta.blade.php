@extends('layouts.academico',['rol'=>min(Request::session()->get('rol'))])
@section('estilos')
{{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css') }}
@endsection
@section('content')
<div class="container">
    <div class="row">        
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Perfil</h3>
                </div>
                <div class="panel-body">
                    <a href="#" class="dato" data-pk="{{ Auth::user()->id }}" id="nombre" data-campo="nombre">
                        {{ Auth::user()->nombre }}</a>
                    <div class="row">
                        <div class="col-md-3">No. de {{ Auth::user()->priv < 5 ? 'trabajador' : 'cuenta' }}</div>
                        <div class="col-md-9">
                            <a href="#" class="dato" data-pk="{{ Auth::user()->id }}" id="nocontrol" data-campo="nocontrol">
                                {{ Auth::user()->nocontrol }}
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">Usuario (login)</div>
                        <div class="col-md-9">
                            <a href="#" class="dato" data-pk="{{ Auth::user()->id }}" id="login" data-campo="login">
                            {{ Auth::user()->login }}
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">Contraseña</div>
                        <div class="col-md-9">
                            <a href="#" class="btn btn-default btn-xs" data-idusuario="{{ Auth::user()->id }}" id="password" data-toggle="modal" data-target="#newpasswd">Cambiar</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">Correo electrónico</div>
                        <div class="col-md-9">
                            <a href="#" class="email" data-type="email" data-pk="{{ Auth::user()->id }}" id="email">
                            {{ Auth::user()->email }}
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">Actividad  general</div>
                        <div class="col-md-9">{{ tesis\User::priv(Auth::user()->priv) }}</div>
                    </div>
                </div>
                @if(Auth::user()->priv == 5)
                <div class="panel-footer">
                    <h4>{{ $tst->first()->programa }}</h4> 
                    <h4>{{ $tst->first()->gen}}</h4>
                </div>
                @endif
            </div>
            @if(Auth::user()->priv < 5)
            <div class="panel panel-default" style="margin-top: 10px;">
                <div class="panel-heading">
                    <h3 class="panel-title">Roles</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Rol</th>
                                <th>Desde</th>
                                <th>Carrera</th>
                                <th>...</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($r as $rol)
                                <tr>
                                    <td>{{ tesis\Rol::rol($rol->rol) }}</td>
                                    @php
                                        $desde = new DateTime($rol->created_at);
                                    @endphp
                                    <td>{{ date_format($desde,'d/m/Y') }}</td>
                                    <td>{{ ($rol->programa!=''?$rol->programa:'--') }}</td>
                                    <td>...</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="newpasswd">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ url('/contrasenaCambiar') }}" method="post" class="form-horizontal">
            {{ csrf_field() }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Cambiar contraseña</h4>
          </div>
          <div class="modal-body">
              <div class="form-group">
                <label for="contrasenaactual" class="col-sm-4 control-label">Contraseña actual</label>
                <div class="col-sm-8">
                  <input type="password" class="form-control" id="contrasenaactual" name="contrasenaactual" required="required">
                </div>
              </div>
              <div class="form-group">
                <label for="contrasenanueva" class="col-sm-4 control-label">Contraseña nueva</label>
                <div class="col-sm-8">
                  <input type="password" class="form-control cn" id="contrasenanueva" name="contrasenanueva" required="required">
                </div>
              </div>
              <div class="form-group">
                <label for="contrasenaconfirmar" class="col-sm-4 control-label">Confirmar contraseña</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="password" class="form-control cn" id="contrasenaconfirmar" name="contrasenaconfirmar" required="required">
                        <div class="input-group-addon"><i class="fas fa-times text-danger" id="cc"></i></div>
                    </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <div class="btn-group">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar <i class="fas fa-times"></i></button>
                <button type="submit" class="btn btn-success disabled" id="submit">Guardar <i class="fas fa-check"></i></button>
            </div>
          </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


@endsection
@section('scripts')

{{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js') }}

<script type="text/javascript">
    $(document).ready(function() {

        $.fn.editable.defaults.mode = 'inline';
        $.fn.editable.defaults.params = function(params){
            params._token = $('meta[name="csrf-token"]').attr("content");
            return params;
        };
        $(".dato").editable({
            type: 'text',
            name: $(this).attr('data-campo'),
            emptytext: '--',
            pk: $(this).attr("data-pk"),
            url: '{{ route('usuarioCambiaDato') }}',
            title: 'Combiar dato del usuario'
        });
        $(".email").editable({
            type: 'email',
            name: 'email',
            emptytext: '--',
            pk: $(this).attr("data-pk"),
            url: '{{ route('usuarioCambiaEmail') }}',
            title: 'Combiar email del usuario',
            success: (response,newValue) => {
                if(response.success === false){
                    return response.msg;
                }else{
                    //console.log(response.success + '<--');
                }
            }
        });

        $('.cn').keyup(function(){
            var cn = $('#contrasenanueva').val();
            var cnc= $('#contrasenaconfirmar').val();
            var cc = $('#cc');
            if(cn === cnc){
                cc.removeClass('fa-times');
                cc.removeClass('text-danger');
                cc.addClass('fa-check');
                cc.addClass('text-success');
                $('#submit').removeClass('disabled');
            }else{
                cc.addClass('fa-times');
                cc.addClass('text-danger');
                cc.removeClass('fa-check');
                cc.removeClass('text-success');
                $('#submit').addClass('disabled');
            }
        });

    });

</script>

@endsection
