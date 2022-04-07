@extends('layouts.admin.app')
@section('content')
    @component('shared.form.form')
        @slot('title','Actualizar administrador')
        @slot('content')
            @include('shared._errors')
            <form method="POST" action="{{route('admin.admins.update',$model->id)}}" enctype="multipart/form-data">
                @method('PATCH')
                <input type="text" name="user_id" value="{{$model->id}}" hidden>
                @include('admin.users.partials._fields',[
                'action'=>'Actualizar',
                'urlReturn'=> route('admin.users.index','q=admins')
                ])
            </form>
        @endslot
    @endcomponent
@endsection
