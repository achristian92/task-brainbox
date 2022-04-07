@extends('layouts.admin.app')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <h3>PANEL DE CONTROL GENERAL</h3>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-group offset-md-8 col-md-4 align-content-center">
                <div class="input-group text-success">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-calendar-grid-58 text-primary"></i></span>
                    </div>
                    <input class="form-control" type="text" id="datepickerdashboard">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <progress-fusionchart :p_progress='{{ json_encode($progress) }}'></progress-fusionchart>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <resume-fusionchart :p_resume="{{ json_encode($resume) }}"></resume-fusionchart>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-10"></div>
                <div class="col-md-2 text-right">
                    <div class="form-group">
                        <select class="form-control form-control-sm" id="dashpaginate">
                            <option value="5" selected="">5 registros</option>
                            <option value="10" {{ request('qtyShow') === "10" ? 'selected' : '' }}>10 registros</option>
                            <option value="20" {{ request('qtyShow') === "20" ? 'selected' : '' }}>20 registros</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <company-more-hours :p_total_hours="{{ json_encode($customerMoreHours)}}"></company-more-hours>
                </div>
                <div class="col-md-6">
                    <company-less-hours :p_total_hours="{{ json_encode($customerLessHours) }}"></company-less-hours>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <user-more-hours :p_total_hours="{{ json_encode($userMoreHours) }}"></user-more-hours>
                </div>
                <div class="col-md-6">
                    <user-less-hours :p_total_hours="{{ json_encode($userLessHours) }}"></user-less-hours>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <tags-trend :p_line="{{ json_encode($lineTags) }}"></tags-trend>
                </div>
                <div class="col-md-6">
                    <tags-percentage :p_tag_percentage="{{ json_encode($tagPercentage) }}"></tags-percentage>
                </div>
            </div>
            <br>
            <Statistics
                :c_customers="{{json_encode($customers)}}"
                :p_yearandmonth="{{ json_encode(request('yearAndMonth') ?: now()->format('Y-m')) }}"
            >

            </Statistics>
        </div>
    </div>

@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready( function () {
            let base_url = '{!! url()->current() !!}';

            let date = '{!! request('yearAndMonth') ?: now()->format('Y-m') !!}'

            $("#datepickerdashboard")
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


            $('#dashpaginate').change(function() {
                window.location.href = base_url+'?yearAndMonth='+date+'&qtyShow='+$(this).val();
            });
        } );
    </script>
@endpush
