@extends('layouts.admin.app')
@section('content')
    <imbox-counter
        :c_my_imbox = "{{$myImbox}}"
        :c_customers = "{{json_encode($customers)}}"
        :c_tags = "{{json_encode($tags)}}"
    >
    </imbox-counter>

@endsection
