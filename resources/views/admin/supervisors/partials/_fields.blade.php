@csrf
<input type="text" id="inputCanCheckAllCounters" value="{{$model->can_check_all_counters ? "1" : "0"}}" hidden>
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">Nombres</label>
                    @include('shared.form.text',['name' => 'name'])
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">Apellidos</label>
                    @include('shared.form.text',['name' => 'last_name'])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">DNI</label>
                    @include('shared.form.text',['name' => 'dni'])
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">Correo electrónico</label>
                    @include('shared.form.email',['name' => 'email'])
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="image">Selecciona una imagen (opcional)</label>
            <div class="card" style="width: 5rem;">
                <img class="card-img-top" src="{{$model->urlImg()}}" alt="Card image cap" >
            </div>
            <br>
            <input type="file" class="form-control-file" name="image" id="image">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="custom-control custom-checkbox mb-3">
            <input class="custom-control-input"
                   id="checkAllCounters"
                   name="can_check_all_counters"
                   type="checkbox"
                   value="{{$model->can_check_all_counters }}"
            />
            <label class="custom-control-label" for="checkAllCounters">Supervisar a todos los contadores</label>
        </div>
    </div>
</div>
<div class="row" id="selectMultipleCounters" style="display: none">
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-control-label" for="exampleFormControlSelect3">Seleccionar multiples contadores</label>
            <select class="js-example-basic-multiple" id="exampleFormControlSelect3" name="counters[]" multiple="multiple">
                @foreach($counters as $counter)
                    <option value="{{$counter->id}}"
                            @if(isset($selectedIds) && in_array($counter->id,$selectedIds))
                            selected="selected"
                            @endif>
                        {{$counter->full_name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
@unless ($model->name)
    <span class="h5 text-muted">
        <i class="ni ni-email-83  mr-2"></i> Le enviaremos un correo con sus credenciales.
    </span>
    <br>
@endunless
@if ($model->password_plain)
    <div class="form-group">
        <label class="form-control-label">Contraseña</label>
        <input type="text" class="form-control" value="{{$model->password_plain}}">
    </div>
@endif
<br>
<button class="btn btn-primary" type="submit">{{$action}}</button>
<a href="{{$urlReturn}}" class="btn btn-secondary" type="submit">Regresar</a>
