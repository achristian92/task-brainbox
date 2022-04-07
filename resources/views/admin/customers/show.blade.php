@extends('layouts.admin.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col">
                    <h3 class="text-primary">{{ $customer->name }}</h3>
                </div>
                <div class="col text-right">
                    <div class="form-group offset-md-8 col-md-4 align-content-center">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                            </div>
                            <input class="form-control" type="text" id="datepickercustomer">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-muted">Estado</label><br>
                        @if ($customer->status)
                        <span  class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-danger">Inactivo</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-muted">Documento</label>
                        <h5>RUC: {{ $customer->ruc }}</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-muted">Dirección</label>
                        <h5>{{ $customer->address }}</h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-muted">Contacto</label>
                        <h5>{{ $customer->contact_name }}</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-muted">Teléfonos</label>
                        <h5>{{ $customer->contact_telephone }}</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-muted">Email</label>
                        <h5>{{ $customer->contact_email }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Act. completadas</h5>
                            <span class="h2 font-weight-bold mb-0">
                                    {{ $resume['qtyCompleted'] }}
                                    <span class="text-success">/</span>
                                    {{ $resume['total'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                <i class="fas fa-list"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0"># Contadores</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $qtyUsers }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Estimado / Real</h5>
                            <span class="h2 font-weight-bold mb-0">
                                    {{ $timeEstimated }}
                                    <span class="text-info">/</span>
                                    {{ $timeReal }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                <i class="fas fa-stopwatch"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Progreso</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $progress }}%</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-light text-white rounded-circle shadow">
                                <i class="ni ni-chart-bar-32"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-9">
                            <progress-fusionchart :p_progress="{{ json_encode($progress) }}"></progress-fusionchart>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <resume-fusionchart :p_resume="{{ json_encode($resume) }}"></resume-fusionchart>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <tags-trend :p_line="{{ json_encode($lineTags) }}"></tags-trend>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <table-imbox :p_activities="{{ json_encode($activities) }}"></table-imbox>
                </div>
            </div>
            <activity-show-component></activity-show-component>
        </div>
    </div>
{{--    <customers-show :c_customer_id="{{ $customer_id }}"></customers-show>--}}
@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready( function () {

            var base_url = '{!! url()->current() !!}';

            let date = '{!! request('yearAndMonth') ?: now()->format('Y-m') !!}'

            $("#datepickercustomer")
                .datetimepicker({
                    format: "MM/YYYY",
                    locale: "es",
                    defaultDate:date,
                    icons: {
                        time: "fa fa-clock-o",
                        date: "fa fa-calendar",
                        up: "fa fa-arrow-up",
                        down: "fa fa-arrow-down",
                        previous: "fa fa-chevron-left",
                        next: "fa fa-chevron-right",
                        today: "fa fa-clock-o",
                        clear: "fa fa-trash-o"
                    }
                })
                .on("dp.change", function (e) {
                    window.location.href = base_url+"?yearAndMonth="+e.date.format('YYYY-MM');
                });

            setTimeout(() => {
                $('#dtTableImbox').DataTable({
                    "dom": '<"top"fl>rt<"bottom"ip>',
                    language: {
                        "url": "{{ URL::asset('datatables.json') }}"
                    },
                });
            }, 3000)



        } );
    </script>
@endpush
@push('styles')
    <style>
        #dtTableImbox_filter {
            float: left !important;
        }

        #dtTableImbox_filter input {
            width: 400px;
            outline: 0px solid #aaa;
        }

        #dtTableImbox_length {
            float: right !important;
        }
        #dtTableImbox_length label {
            display:flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush


