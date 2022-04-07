@csrf
<h2>Empresa</h2>
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">Nombre</label>
                    @include('shared.form.text',['name' => 'name'])
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">Dirección</label>
                    @include('shared.form.text',['name' => 'address'])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">Ruc</label>
                    @include('shared.form.text',['name' => 'ruc'])
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-control-label">Horas de trabajo mensual</label>
                    <input type="number" step="any" name="hours" class="form-control" value="{{ old('hours') ? old('hours') : $model->hours}}"  />
                </div>
            </div>
        </div>
        <h3>Contacto</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label">Correo</label>
                    <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email') ? old('contact_email') : $model->contact_email}}"  />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label">Nombre</label>
                    @include('shared.form.text',['name' => 'contact_name'])
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label">Número teléfono</label>
                    @include('shared.form.text',['name' => 'contact_telephone'])
                </div>
            </div>
        </div>
    </div>
    <div class="col md-4">
        <div class="form-group">
            <label for="image">Selecciona una imagen de la empresa (opcional)</label>
            <div class="card" style="width: 7rem;">
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
                   id="sendNotifyExcessTime"
                   name="notify_excess_time"
                   type="checkbox"
                   value="{{$model->notify_excess_time}}"/>
            <label class="custom-control-label" for="sendNotifyExcessTime">Notificar si excedo las horas mensuales</label>
        </div>
    </div>
</div>


<button class="btn btn-primary" type="submit">{{$action}}</button>
<a href="{{$urlReturn}}" class="btn btn-secondary" type="submit">Regresar</a>
