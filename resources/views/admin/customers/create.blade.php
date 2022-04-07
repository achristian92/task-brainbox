@extends('layouts.admin.app')
@section('content')
    @component('shared.form.form')
        @slot('title','Nuevo cliente')
        @slot('content')
            @include('shared._errors')
            <form method="POST" action="{{route('admin.customers.store')}}" enctype="multipart/form-data">
                @include('admin.customers.partials._fields',[
                'action'=>'Guardar',
                'urlReturn'=> route('admin.customers.index')
                ])
            </form>
        @endslot
    @endcomponent
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            var can_check_all_customers = $('#sendNotifyExcessTime').val();

            console.log(can_check_all_customers);

            if ( can_check_all_customers === "1") {
                $("#sendNotifyExcessTime").prop( "checked", true );
                $('#checkAllCustomers').val(1);
            } else {
                $("#checkAllCustomers").prop('checked', false);
                $('#checkAllCustomers').val(0)
            }

            $('#sendNotifyExcessTime').change(function(){
                if(!$(this).prop('checked')){
                    $('#sendNotifyExcessTime').val(0)
                }else{
                    $('#sendNotifyExcessTime').val(1)
                }
            });
        });
    </script>

@endpush
