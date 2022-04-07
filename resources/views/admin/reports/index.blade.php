@extends('layouts.admin.app')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <report-users></report-users>
        </div>
        <div class="col-md-4">
            <report-customers></report-customers>
        </div>
        <div class="col-md-4">
            <report-activities></report-activities>
        </div>
    </div>

    <report-users-planned-vs-real-component :c_users="{{json_encode($users)}}"></report-users-planned-vs-real-component>
    <report-users-time-worked-by-customer-component :c_users="{{json_encode($users)}}"></report-users-time-worked-by-customer-component>
    <report-users-time-worked-by-day-component :c_users="{{json_encode($users)}}"></report-users-time-worked-by-day-component>

    <report-customers-time-worked-by-month-component></report-customers-time-worked-by-month-component>
    <report-customers-list-users-working-component :c_customers="{{json_encode($customers)}}"></report-customers-list-users-working-component>
    <report-customers-history-last-six-months-component></report-customers-history-last-six-months-component>
    <report-tags :c_customers="{{json_encode($customers)}}"></report-tags>

    <report-activities-list-activities-component></report-activities-list-activities-component>
@endsection
