@extends('layouts.admin.app')
@section('content')
    @component('components.list')
        @slot('title','Lista de Usuarios ['. count($users).']')
        @slot('actions')
            @if (Auth::user()->isSuperAdmin())
                <a href="{{ route('admin.users.export') }}"
                   class="btn btn-primary btn-sm">
                    Exportar
                </a>
            @endif
            @include('shared._btn-add',['url'=>  route('admin.users.create')])
        @endslot
        @slot('filters')@endslot
        @slot('table')
            <table class="table align-items-center table-flush border-bottom-0" id="dtUsers">
                <thead class="thead-light">
                <tr>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @each('admin.users.partials._row', $users,'user', 'shared._empty')
                </tbody>
            </table>
        @endslot
    @endcomponent
    @include('admin.customers.partials.import')
    @include('admin.customers.partials.modal-list-assigned-users')
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready( function () {
            $('#dtUsers').DataTable({
                "dom": '<"top"fl>rt<"bottom"ip>',
                language: {
                    "url": "{{ URL::asset('datatables.json') }}"
                },

            });
        } );
    </script>
@endpush
@push('styles')
    <style>
        #dtUsers_filter {
            float: left !important;
        }

        #dtUsers_filter input {
            width: 400px;
            outline: 0px solid #aaa;
        }

        #dtUsers_length {
            float: right !important;
        }
        #dtUsers_length label {
            display:flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush




