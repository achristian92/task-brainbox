@csrf
<input type="text" id="inputCanCheckAllCustomers" value="{{$model->can_check_all_customers ? "1" : "0"}}" hidden>
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
            <input type="file" class="form-control-file" name="image" id="image"  title="Escoger">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="custom-control custom-checkbox mb-3">
            <input class="custom-control-input"
                   id="checkAllCustomers"
                   name="can_check_all_customers"
                   type="checkbox"
                   value="{{$model->can_check_all_customers }}"
            />
            <label class="custom-control-label" for="checkAllCustomers">Trabajar en todos los clientes</label>
        </div>
    </div>
</div>
<div class="row" id="selectMultipleCustomers" style="display: none">
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-control-label" for="formSelectCustomers">Seleccionar multiples clientes</label>
            <select class="js-customer-select-multiple" id="formSelectCustomers" name="customers[]" multiple="multiple">
                @foreach($customers as $customer)
                    <option value="{{$customer->id}}"
                            @if(isset($selectedIds) && in_array($customer->id,$selectedIds))
                            selected="selected"
                        @endif>
                        {{$customer->name}}
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
@if ($model->id)
    <h4>Supervisores</h4>
    <ul>
        @foreach($supervisors as $super)
            <li> {{$super}} </li>
        @endforeach
    </ul>

@endif
<br>
<button class="btn btn-primary" type="submit">{{$action}}</button>
<a href="{{$urlReturn}}" class="btn btn-secondary" type="submit">Regresar</a>
