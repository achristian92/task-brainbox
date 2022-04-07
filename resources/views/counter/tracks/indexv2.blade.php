@extends('layouts.admin.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6"><h3 class="mb-0 text-primary">{{ $user }}</h3></div>
                <div class="col-md-6 text-right" >
                    <div class="form-group offset-md-8 col-md-4 align-content-center">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                            </div>
                            <input class="form-control" type="text" id="mytracksv2month">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 col-md-6">
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
        <div class="col-xl-4 col-md-6">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Estimado / Real</h5>
                            <span class="h2 font-weight-bold mb-0">
                                    {{ $timeWorked }}
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
        <div class="col-xl-4 col-md-6">
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
                            <progress-fusionchart :p_progress='{{ json_encode($progress) }}'></progress-fusionchart>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <resume-fusionchart :p_resume="{{ json_encode($resume) }}"></resume-fusionchart>
                </div>
            </div>
            <br>
            <br>
            <div class="table-responsive table-size-12">
                <admin-imbox-basic :p_activities="{{ json_encode($activities) }}"></admin-imbox-basic>
            </div>


        </div>
    </div>
    <activity-show-component></activity-show-component>

@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready( function () {
            let base_url = '{!! url()->current() !!}';
            let date = '{!! request('yearAndMonth') ?: now()->format('Y-m') !!}'

            $("#mytracksv2month")
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
                    window.location.href = base_url+"?yearAndMonth="+e.date.format('YYYY-MM')

                });


            $('#dtImboxBasic').DataTable({
                "dom": '<"top"fl>rt<"bottom"ip>',
                language: {
                    "url": "{{ URL::asset('datatables.json') }}"
                },
            });

        } );
    </script>
@endpush
@push('styles')
    <style>
        #dtImboxBasic_filter {
            float: left !important;
        }

        #dtImboxBasic_filter input {
            width: 400px;
            outline: 0px solid #aaa;
        }

        #dtImboxBasic_length {
            float: right !important;
        }
        #dtImboxBasic_length label {
            display:flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush

