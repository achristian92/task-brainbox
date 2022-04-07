<div class="modal fade" id="modalCreateTag" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Anadir etiqueta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" id="form-tag">
                <div class="modal-body">
                        <div class="form-group">
                            <label class="form-control-label" for="nametagInput">Nombre de la etiqueta</label>
                            <input type="text" class="form-control" id="nametagInput" name="name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="colorselector">Color de la etiqueta</label>
                            <select class="form-control" id="colorselector" name="color">
                                @foreach(\App\Repositories\Tools\Color::COLOR_TAG as $key => $color)
                                    <option value="{{$color}}" data-color="{{$color}}">"Color {{$color}}"</option>
                                @endforeach
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-outline-default  ml-auto" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
