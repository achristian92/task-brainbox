@extends('layouts.admin.app')
@section('content')
    <show-user-track :c_user_id="{{Auth::id()}}"></show-user-track>
@endsection
