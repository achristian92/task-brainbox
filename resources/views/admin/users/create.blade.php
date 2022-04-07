@extends('layouts.admin.app')
@section('content')
    @component('shared.form.form')
        @slot('title','Nuevo usuario')
        @slot('content')
            @include('shared._errors')
            <form method="POST" action="{{route('admin.users.store')}}" enctype="multipart/form-data">
                @include('admin.users.partials._fields',[
                'action'=>'Guardar',
                'urlReturn'=> route('admin.users.index')
                ])
            </form>
        @endslot
    @endcomponent
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('.js-roles-multiple').select2();
            $('.js-admin-super-multiple').select2();
            $('.js-customer-select-multiple').select2();

            // Asign
            var monitored_all = $('#inputCanCheckByAll').val();

            if (monitored_all === "0") {
                $('#selectMultipleAdminORSuper').hide();
                $("#checkByAll").prop( "checked", true );
                $('#checkByAll').val(1)

            } else {
                $("#checkByAll").prop('checked', false);
                $('#selectMultipleAdminORSuper').show();
                $('#checkByAll').val(0)
            }

            $('.can_check_all').change(function(){
                if (!$(this).prop('checked')){
                    $('#selectMultipleAdminORSuper').show();
                    $('#checkByAll').val(0)
                } else {
                    $('#selectMultipleAdminORSuper').hide();
                    $('#checkByAll').val(1)
                }
            });

            // Customer
            let check_all_customers = $('#inputCanCheckAllCustomers').val();
            if (check_all_customers === "0") {
                $('#selectMultipleCustomers').hide();
                $("#checkAllCustomers").prop( "checked", true );
                $('#checkAllCustomers').val(1)
            } else {
                $("#checkAllCustomers").prop('checked', false);
                $('#selectMultipleCustomers').show();
                $('#checkAllCustomers').val(0)
            }

            $('.can_check_all_customers').change(function(){
                if(!$(this).prop('checked')){
                    $('#selectMultipleCustomers').show();
                    $('#checkAllCustomers').val(0)
                }else{
                    $('#selectMultipleCustomers').hide();
                    $('#checkAllCustomers').val(1)
                }
            });

        });

    </script>
@endpush
