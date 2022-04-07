@extends('layouts.admin.app')
@section('content')
    @component('components.list')
        @slot('title','Lista de Clientes ['. count($customers).']')
        @slot('actions')
            @include('shared._btn-add',['url'=>  route('admin.customers.create')])
            @if (Auth::user()->isSuperAdmin())
                <a href="{{ route('admin.customers.export') }}"
                        class="btn btn-primary btn-sm">
                    Exportar
                </a>
                <button type="button"
                        class="btn btn-primary btn-sm"
                        data-toggle="modal"
                        data-target="#importModalCustomer">
                    Importar
                </button>
            @endif
        @endslot
        @slot('filters')@endslot
        @slot('table')
            <table class="table align-items-center table-flush border-bottom-0" id="dtCustomers">
                <thead class="thead-light">
                <tr>
                    <th>Cliente</th>
                    <th>RUC</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @each('admin.customers.partials._row', $customers,'customer', 'shared._empty')
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
            $('#dtCustomers').DataTable({
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
        #dtCustomers_filter {
            float: left !important;
        }

        #dtCustomers_filter input {
            width: 400px;
            outline: 0px solid #aaa;
        }

        #dtCustomers_length {
            float: right !important;
        }
        #dtCustomers_length label {
            display:flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush

