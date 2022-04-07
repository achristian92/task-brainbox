@extends('layouts.admin.app')
@section('content')
    @component('components.list')
        @slot('title','Documentos ['. count($documents).']')
        @slot('actions')@endslot
        @slot('filters')@endslot
        @slot('table')
            <table class="table align-items-center table-flush border-bottom-0" id="dtDocuments">
                <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Descripcion</th>
                </tr>
                </thead>
                <tbody>
                @foreach($documents as $document)
                    <tr>
                        <td width="2%"> <i class="fa fa-fingerprint mr-1"></i> {{ $document->id }}</td>
                        <td>
                            {{ $document->name }} <br>
                            <small><a href="{{$document->url_file}}" class="text-black-50"> <i class="fa fa-download mr-1"></i> {{ $document->url_file }} </a></small> <br>
                            <small>
                                <i class="far fa-hand-pointer mr-1 ml-1"></i> {{ $document->type }} |
                                <i class="far fa-user mr-1 ml-1"></i> {{ $document->user_full_name }} |
                                <i class="far fa-clock mr-1 ml-1"></i> {{ \Carbon\Carbon::parse($document->created_at)->format('d/m/y h:i') }}
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
            $('#dtDocuments').DataTable({
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
        #dtDocuments_filter {
            float: left !important;
        }

        #dtDocuments_filter input {
            width: 400px;
            outline: 0px solid #aaa;
        }

        #dtDocuments_length {
            float: right !important;
        }
        #dtDocuments_length label {
            display:flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush








