@extends('layouts.admin.app')
@section('content')

    <view-fullcalendar
        :c_users       = "{{json_encode($usersMonitor)}}"
        :c_type_status = "{{json_encode($status)}}">
    </view-fullcalendar>

    <activity-component
        :c_users      = "{{json_encode($usersAll)}}"
        :c_customers  = "{{json_encode($customers)}}"
        :c_tags       = "{{json_encode($tags)}}">
    </activity-component>


@endsection
