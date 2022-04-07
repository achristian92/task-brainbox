@extends('layouts.admin.app')
@section('content')
    @component('shared.form.form')
        @slot('title','Nuevo supervisor')
        @slot('content')
            @include('shared._errors')
            <form method="POST" action="{{route('admin.supervisors.store')}}" enctype="multipart/form-data">
                @include('admin.supervisors.partials._fields',[
                'counters' => $counters,
                'action'=>'Guardar',
                'urlReturn'=> route('admin.users.index','q=supervisors')
                ])
            </form>
        @endslot
    @endcomponent
@endsection
@push('js')
    <script src="{{asset('js/supervisor.js')}}"></script>
@endpush
