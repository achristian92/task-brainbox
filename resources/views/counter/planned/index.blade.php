@extends('layouts.admin.app')
@section('content')

    <view-fullcalendar-counter-planned
        :c_customers   = "{{json_encode($customers)}}"
        :c_type_status = "{{json_encode($status)}}">
    </view-fullcalendar-counter-planned>


    <activity-component
        :c_customers  = "{{json_encode($customers)}}"
        :c_tags       = "{{json_encode($tags)}}">
    </activity-component>

    <import-work-plan-component></import-work-plan-component>
    <duplicate-work-plan-component></duplicate-work-plan-component>
    <mass-destroy-work-plan-component></mass-destroy-work-plan-component>
@endsection
