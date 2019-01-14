{{-- Modal para ver detalles del tesista: titrulo de tesis, descripcion, asesor, coasesro, etc. --}}

<div class="modal fade" tabindex="-1" role="dialog" id="detalletesista">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close cancelar" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Tesistas: <span class="nombre-tesista bg-warning"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info detalles-tesis">
                        <div class="row">
                            <div class="col-sm-2 etitulo">Titulo:</div>
                            <div class="col-sm-10 dtitulo"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 edescripcion">Descripción:</div>
                            <div class="col-sm-10 ddescripcion"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 easesor">Asesor:</div>
                            <div class="col-sm-10 dasesor"></div>
                        </div>                    
                        <div class="row">
                            <div class="col-sm-2 ecoasesores">Coasesores:</div>
                            <div class="col-sm-10 dcoasesores"></div>
                        </div>                    
                        <div class="row">
                            <div class="col-sm-2 erevisores">Revisores:</div>
                            <div class="col-sm-10 drevisores"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 eestado">Estado:</div>
                            <div class="col-sm-10 destado"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-right">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
