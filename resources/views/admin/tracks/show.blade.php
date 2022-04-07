@extends('layouts.admin.app')
@section('content')
    <show-user-track :c_user_id="{{$user_id}}"></show-user-track>
@endsection
