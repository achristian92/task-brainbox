@extends('layouts.admin.app')
@section('content')
    @component('shared.form.form')
        @slot('title','Actualizar supervisor')
        @slot('content')
            @include('shared._errors')
            <form method="POST" action="{{route('admin.supervisors.update',$model->id)}}" enctype="multipart/form-data">
                @method('PATCH')
                <input type="text" name="user_id" value="{{$model->id}}" hidden>
                @include('admin.supervisors.partials._fields',[
                'action'=>'Actualizar',
                'urlReturn'=> route('admin.users.index','q=supervisors')
                ])
            </form>
        @endslot
    @endcomponent
@endsection
@push('js')
    <script src="{{asset('js/supervisor.js')}}"></script>
@endpush
