@extends('layouts.admin.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Ajustes del proyecto</div>
                    <div class="card-body">
                        @include('shared._errors')
                        <form method="POST" action="{{route('admin.settings.update',$setup)}}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="text" id="inputCanSendCredentials" value="{{$setup->send_credentials ? "1" : "0"}}" hidden>
                            <input type="text" id="inputNotifyOverdueAct" value="{{$setup->send_overdue ? "1" : "0"}}" hidden>
                            <input type="text" id="inputNotifyDeadline" value="{{$setup->notify_deadline ? "1" : "0"}}" hidden>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="setupCompany">Empresa</label>
                                            <input type="text" class="form-control" id="setupCompany" name="company" value="{{$setup->company}}" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="setupProject">Nombre proyecto</label>
                                            <input type="text" class="form-control" id="setupProject" name="project" value="{{$setup->project}}" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="setupSend">Email remitente</label>
                                            <input type="text" class="form-control" id="setupSend" name="send_email"  value="{{$setup->send_email}}" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="setupSend">Total horas mensual</label>
                                            <input type="number" class="form-control" id="setupSend" name="hours"  value="{{$setup->hours}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="row text-center mb-3">
                                        <div class="card" style="width: 18rem;">
                                            <img class="card-img-top" src="{{ $setup->url_logo }}" alt="Card image cap">
                                        </div>
                                        <input type="file" class="form-control-file" name="image" id="image">
                                    </div>
                                </div>
                            </div>
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="senCredentialUser"
                                       name="send_credentials"
                                       value="{{ $setup->send_credentials}}"
                                       />
                                <label class="form-check-label" for="senCredentialUser">Enviar credenciales a los usuarios</label>
                            </div>
                            <br>
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="sendOverdue"
                                       name="send_overdue"
                                       value="{{ $setup->send_overdue }}"
                                       />
                                <label class="form-check-label" for="sendOverdue">Notificar actividades no realizadas</label>
                            </div>
                            <br>
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="sendDeadline"
                                       name="notify_deadline"
                                       value="{{ $setup->notify_deadline }}"
                                />
                                <label class="form-check-label" for="sendDeadline">Notificar actividades con fecha límite</label>
                            </div>
                            <br>

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
@push('js')
    <script>
        $(document).ready(function() {
            var can_send_credentials = $('#inputCanSendCredentials').val();
            var send_overdue_act = $('#inputNotifyOverdueAct').val();
            var send_deadline = $('#inputNotifyDeadline').val();

            if (can_send_credentials === "1") {
                $("#senCredentialUser").prop( "checked", true );
            } else {
                $("#senCredentialUser").prop('checked', false);
                $('#senCredentialUser').val(0)
            }

            $('#senCredentialUser').change(function(){
                if(!$(this).prop('checked')){
                    $('#senCredentialUser').val(0)
                }else{
                    $('#senCredentialUser').val(1)
                }
            });

            if (send_overdue_act === "1") {
                $("#sendOverdue").prop( "checked", true );
            } else {
                $("#sendOverdue").prop('checked', false);
                $('#sendOverdue').val(0)
            }

            $('#sendOverdue').change(function(){
                if(!$(this).prop('checked')){
                    $('#sendOverdue').val(0)
                }else{
                    $('#sendOverdue').val(1)
                }
            });

            if (send_deadline === "1") {
                $("#sendDeadline").prop( "checked", true );
            } else {
                $("#sendDeadline").prop('checked', false);
                $('#sendDeadline').val(0)
            }

            $('#sendDeadline').change(function(){
                if(!$(this).prop('checked')){
                    $('#sendDeadline').val(0)
                }else{
                    $('#sendDeadline').val(1)
                }
            });

        });
    </script>
@endpush
