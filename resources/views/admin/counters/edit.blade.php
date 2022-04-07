@extends('layouts.admin.app')
@section('content')
    @component('shared.form.form')
        @slot('title','Actualizar contador')
        @slot('content')
            @include('shared._errors')
            <form method="POST" action="{{route('admin.counters.update',$model->id)}}" enctype="multipart/form-data">
                @method('PATCH')
                <input type="text" name="user_id" value="{{$model->id}}" hidden>
                @include('admin.counters.partials._fields',[
                'action'=>'Actualizar',
                'urlReturn'=> route('admin.users.index','q=counters')
                ])
            </form>
        @endslot
    @endcomponent
@endsection
@push('js')
    <script src="{{asset('js/counter.js')}}"></script>
@endpush
