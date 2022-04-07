@extends('layouts.admin.app')
@section('content')
    @component('shared.form.form')
        @slot('title','Nuevo administrador')
        @slot('content')
            @include('shared._errors')
            <form method="POST" action="{{route('admin.admins.store')}}" enctype="multipart/form-data">
                @include('admin.users.partials._fields',[
                'action'=>'Guardar',
                'urlReturn'=> route('admin.users.index','q=admins')
                ])
            </form>
        @endslot
    @endcomponent
@endsection
