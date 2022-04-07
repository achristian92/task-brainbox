@extends('layouts.admin.app')
@section('content')
    <imbox-admin></imbox-admin>
@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready( function () {
            $('#dtImbox').DataTable({
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
        #dtImbox_filter {
            float: left !important;
        }

        #dtImbox_filter input {
            width: 400px;
        }

        #dtImbox_length {
            float: right !important;
        }
        #dtImbox_length label {
            display:flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush
