<form method="post" v-on:submit.prevent="nuevaCal">
<div class="modal fade" tabindex="-1" role="dialog" id="califica">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Asignar calificación: <span class="bg-warning" id="ttitulo"></span></h4>
      </div>
      <div class="modal-body">
        <div class="form-group form-group-lg">
          <label for="eval" class="control-label">Parcial</label>
          <input type="text" name="eval" v-model="eval"  class="form-control form-control-lg" readonly="readonly">

        </div>
        <div class="form-group form-group-lg">
          <label for="cal" class="control-label">Calificación</label>
          <input type="number" v-model="nCal" max="10" min="0" maxlength="3" name="cal" class="form-control form-control-lg">
        </div>
        <div class="form-group form-group-lg">
          <label for="obs" class="control-label">Observaciones</label>
          <input type="text" v-model="obs" name="obs" class="form-control form-control-lg">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i></button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>
