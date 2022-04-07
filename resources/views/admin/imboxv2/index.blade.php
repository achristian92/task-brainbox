@extends('layouts.admin.app')
@section('content')
    <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-text" role="tablist">
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 {{ request()->input('typeTab') === "today" ? 'active' : '' }}" href="{{ route('admin.imboxv2.index','typeTab=today') }}">
                <span class="h4"><i class="fas fa-calendar-day mr-1"></i> Hoy</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 {{ request()->input('typeTab') === "proximate" ? 'active' : '' }}" href="{{ route('admin.imboxv2.index','typeTab=proximate') }}" >
                <span class="h4"><i class="far fa-arrow-alt-circle-right mr-1"></i> Próximas</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 {{ request()->input('typeTab') === "overdue" ? 'active' : '' }}" href="{{ route('admin.imboxv2.index','typeTab=overdue') }}">
                <span class="h4 text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Vencidas</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 {{ request()->input('typeTab') === "evaluation" ? 'active' : '' }}" href="{{ route('admin.imboxv2.index','typeTab=evaluation') }}">
                <span class="h4"><i class="far fa-thumbs-up mr-1"></i> Aprobar/Rechazar</span>
            </a>
        </li>
    </ul>
    <br>
    @component('components.list')
        @slot('title',$title. ' ['. count($activities).']')
        @slot('actions')
            @if(request()->input('typeTab') === "overdue" || request()->input('typeTab') === "evaluation")
                <div class="form-group offset-md-8 col-md-4 align-content-center">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                        </div>
                        <input class="form-control" type="text" id="datepickeroerevaluation">
                    </div>
                </div>
            @endif
        @endslot
        @slot('filters')@endslot
        @slot('table')
            @if(request()->input('typeTab') === "evaluation")
                <admin-imbox-evaluate :p_activities="{{ json_encode($activities) }}"></admin-imbox-evaluate>
            @else
                <admin-imbox-basic :p_activities="{{ json_encode($activities) }}"></admin-imbox-basic>
            @endif
        @endslot
    @endcomponent
    <activity-show-component></activity-show-component>

@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready( function () {
            var base_url = '{!! url()->current() !!}';

            let date = '{!! request('yearAndMonth') ?: now()->format('Y-m') !!}'
            var q = '{!! request()->input('typeTab','today') !!}'


            $("#datepickeroerevaluation")
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
                    window.location.href = base_url+'?typeTab='+q+"&yearAndMonth="+e.date.format('YYYY-MM');
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

