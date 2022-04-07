@extends('layouts.admin.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Cuenta</div>
                    <div class="card-body">
                        @include('shared._errors')
                        <form method="POST" action="{{route('account.update')}}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="form-group row">
                                <label for="staticName" class="col-sm-2 col-form-label">Nombres</label>
                                <div class="col-sm-4">
                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           id="staticName"
                                           value="{{old('name',$user->name)}}"
                                           required>
                                </div>
                                <label for="staticLastName" class="col-sm-2 col-form-label">Apellidos</label>
                                <div class="col-sm-4">
                                    <input type="text"
                                           name="last_name"
                                           class="form-control"
                                           id="staticLastName"
                                           value="{{old('last_name',$user->last_name)}}"
                                           required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticDNI" class="col-sm-2 col-form-label">DNI</label>
                                <div class="col-sm-4">
                                    <input type="text"
                                           class="form-control"
                                           name="nro_document"
                                           id="staticDNI"
                                           value="{{old('nro_document',$user->nro_document)}}"
                                           required>
                                </div>
                                <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-4">
                                    <input type="text"
                                           class="form-control-plaintext"
                                           id="staticEmail"
                                           value="{{old('email',$user->email)}}"
                                           readonly
                                           required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password"
                                           name="password"
                                           class="form-control"
                                           id="inputPassword"
                                           placeholder="Nueva contraseña">
                                </div>
                            </div>
                            <div class="row">
                                <button class="btn btn-primary ml-2" type="submit">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
