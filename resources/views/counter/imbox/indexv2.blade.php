@extends('layouts.admin.app')
@section('content')
    <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-text" role="tablist">
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 {{ request()->input('typeTab') === "today" ? 'active' : '' }}" href="{{ route('counter.imboxv2.index','typeTab=today') }}">
                <span class="h4"><i class="fas fa-calendar-day mr-1"></i> Hoy</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 {{ request()->input('typeTab') === "proximate" ? 'active' : '' }}" href="{{ route('counter.imboxv2.index','typeTab=proximate') }}" >
                <span class="h4"><i class="far fa-arrow-alt-circle-right mr-1"></i> Próximas</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 {{ request()->input('typeTab') === "overdue" ? 'active' : '' }}" href="{{ route('counter.imboxv2.index','typeTab=overdue') }}">
                <span class="h4 text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Vencidas</span>
            </a>
        </li>
    </ul>
    <br>
    @component('components.list')
        @slot('title',$title. ' ['. count($activities).']')
        @slot('actions')
            @if(request()->input('typeTab') === "today")
                <add-new-activity></add-new-activity>
            @endif
            @if(request()->input('typeTab') === "overdue")
                <div class="form-group offset-md-8 col-md-4 align-content-center">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                        </div>
                        <input class="form-control" type="text" id="my_imbox_overdue_evaluation">
                    </div>
                </div>
            @endif
        @endslot
        @slot('filters')@endslot
        @slot('table')
            <counter-imbox-action :p_activities="{{ json_encode($activities) }}" :p_tab="{{ json_encode($tab) }}"></counter-imbox-action>
        @endslot
    @endcomponent
    <activity-show-component></activity-show-component>
    <activity-new-component :p_customers="{{ json_encode($customers) }}" :p_tags="{{ json_encode($tags) }}"></activity-new-component>
    <activity-partial-component></activity-partial-component>
    <sub-activity-component></sub-activity-component>
@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready( function () {

            var base_url = '{!! url()->current() !!}';
            var q = '{!! request()->input('typeTab','today') !!}'
            let date = '{!! request('yearAndMonth') ?: now()->format('Y-m') !!}'

            $("#my_imbox_overdue_evaluation")
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


            $('#dtMyImboxAction').DataTable({
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
        #dtMyImboxAction_filter {
            float: left !important;
        }

        #dtMyImboxAction_filter input {
            width: 400px;
            outline: 0px solid #aaa;
        }

        #dtMyImboxAction_length {
            float: right !important;
        }
        #dtMyImboxAction_length label {
            display:flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush



{{--@extends('layouts.admin.app')--}}
{{--@section('content')--}}
{{--    <imbox-counter--}}
{{--        :c_my_imbox = "{{$myImbox}}"--}}
{{--        :c_customers = "{{json_encode($customers)}}"--}}
{{--        :c_tags = "{{json_encode($tags)}}"--}}
{{--    >--}}
{{--    </imbox-counter>--}}

{{--@endsection--}}
