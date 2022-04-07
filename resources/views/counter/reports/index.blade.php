@extends('layouts.admin.app')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <report-users></report-users>
        </div>
    </div>

    <report-users-planned-vs-real-component :c_users="{{json_encode($users)}}"></report-users-planned-vs-real-component>
    <report-users-time-worked-by-customer-component :c_users="{{json_encode($users)}}"></report-users-time-worked-by-customer-component>
    <report-users-time-worked-by-day-component :c_users="{{json_encode($users)}}"></report-users-time-worked-by-day-component>

@endsection
