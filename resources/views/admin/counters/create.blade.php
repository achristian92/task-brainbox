@extends('layouts.admin.app')
@section('content')
    @component('shared.form.form')
        @slot('title','Nuevo contador')
        @slot('content')
            @include('shared._errors')
            <form method="POST" action="{{route('admin.counters.store')}}" enctype="multipart/form-data">
                @include('admin.counters.partials._fields',[
                'action'=>'Guardar',
                'urlReturn'=> route('admin.users.index','q=counters')
                ])
            </form>
        @endslot
    @endcomponent
@endsection
@push('js')
    <script src="{{asset('js/counter.js')}}"></script>
@endpush
