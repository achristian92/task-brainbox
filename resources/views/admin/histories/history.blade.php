@extends('layouts.admin.app')
@section('content')
    @component('components.list')
        @slot('title','Historial ['. count($histories).']')
        @slot('actions')@endslot
        @slot('filters')@endslot
        @slot('table')
            <table class="table align-items-center table-flush border-bottom-0" id="dtHistory">
                <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Descripcion</th>
                </tr>
                </thead>
                <tbody>
                @foreach($histories as $history)
                    <tr>
                        <td width="2%"> <i class="fa fa-fingerprint mr-1"></i> {{ $history->id }}</td>
                        <td>
                            {{ $history->description }} <br>
                            <small>
                                <i class="far fa-hand-pointer mr-1 ml-1"></i> {{ $history->type }} |
                                <i class="far fa-user mr-1 ml-1"></i> {{ $history->user_full_name }} |
                                <i class="far fa-clock mr-1 ml-1"></i> {{ \Carbon\Carbon::parse($history->created_at)->format('d/m/y h:i') }}
                            </small>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent
@endsection


@push('js')
    <script type="text/javascript">
        $(document).ready( function () {
            $('#dtHistory').DataTable({
                "dom": '<"top"fl>rt<"bottom"ip>',
                "order": [[ 0, "desc" ]],
                language: {
                    "url": "{{ URL::asset('datatables.json') }}"
                },

            });
        } );
    </script>
@endpush
@push('styles')
    <style>
        #dtHistory_filter {
            float: left !important;
        }

        #dtHistory_filter input {
            width: 400px;
            outline: 0px solid #aaa;
        }

        #dtHistory_length {
            float: right !important;
        }
        #dtHistory_length label {
            display:flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush





