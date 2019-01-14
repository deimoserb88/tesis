{{-- Modal para agregar un nuevo registro de usuario --}}

<div class="modal fade" tabindex="-1" role="dialog" id="nuevousuario">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('usuarioGuardar') }}" method="post" id="usuarioGuardar"  class="form-horizontal">
             {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close cancelar" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar usuario</h4>
                </div>
                <div class="modal-body">

                  <div class="form-group{{ isset($errores)?($errores->has('nombre') ? ' has-error' : ''):'' }}">
                    <label for="nombre" class="col-sm-4 control-label">Nombre completo</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="nombre" name="nombre" required="required" autofocus="autofocus" value="{{ isset($request)?$request->nombre:'' }}">
                    </div>
                  </div>
                  <div class="form-group{{ isset($errores)?($errores->has('nocontrol') ? ' has-error' : ''):''  }}">
                    <label for="nocontrol" class="col-sm-4 control-label">Número de {{ $tipo_usuario<5?'trabajador':'cuenta' }}</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="nocontrol" name="nocontrol" required="required" maxlength="8" value="{{ isset($request)?$request->nocontrol:'' }}">
                    </div>
                  </div>
                  <div class="form-group{{ isset($errores)?($errores->has('email') ? ' has-error' : ''):'' }}">
                    <label for="email" class="col-sm-4 control-label">Correo electrónico</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="email" name="email" required="required" value="{{ isset($request)?$request->email:'' }}" placeholder="Debe ser institucional (@ucol.mx)">
                    </div>
                  </div>
                  <div class="form-group{{ isset($errores)?($errores->has('password') ? ' has-error' : ''):'' }}">
                    <label for="password" class="col-sm-4 control-label">Contraseña</label>
                    <div class="col-sm-8">
                      <input type="password" class="form-control" id="password" name="password" required="required">
                    </div>
                  </div>

                      <div class="form-group{{ isset($errores)?($errores->has('priv') ? ' has-error' : ''):'' }}">
                        <label for="password" class="col-sm-4 control-label">Tipo usuario</label>
                        <div class="col-sm-8">
                            @if(Auth::user()->priv<3)
                            <select name="priv" class="form-control" id="priv">
                                @foreach(range(Auth::user()->priv+1,5) as $r)
                                    <option value="{{ $r }}" {{ isset($request)?($r == $request->priv?'selected="selected"':''):'' }}>{{ tesis\User::priv($r) }}</option>
                                @endforeach
                            </select>
                            @else
                                <label>{{ tipoUsuario($tipo_usuario) }}</label>
                                <input type="hidden" name="priv" value="{{ $tipo_usuario }}">
                            @endif
                        </div>
                      </div>
                      <div class="form-group hidden genprog">
                        <label for="carr" class="col-sm-4 control-label">Programa</label>
                        <div class="col-sm-8">
                            <select name="carr" class="form-control" id="carr">
                                @foreach($p as $prog)
                                    <option value="{{ $prog->id }}">{{ $prog->programa }}</option>
                                @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="form-group hidden genprog">
                        <label for="gen" class="col-sm-4 control-label">Generación</label>
                        <div class="col-sm-3">
                            <select name="gen" class="form-control" id="gen">
                                    <option value="{{ date("Y") }}">{{ date("Y") }}</option>
                                    <option value="{{ date("Y")+1 }}">{{ date("Y")+1 }}</option>
                                    <option value="{{ date("Y")+2 }}">{{ date("Y")+2 }}</option>
                            </select>
                        </div>
                        <div class="col-md-5">&nbsp;</div>
                      </div>


                </div>
                <div class="modal-footer">
                    <div class="btn-group" rol="group">
                        <button type="button" class="btn btn-danger cancelar" data-dismiss="modal">Cancelar <i class="fas fa-times"></i></button>
                        <button type="submit" class="btn btn-success">Guardar <i class="fas fa-check"></i></button>
                    </div>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

