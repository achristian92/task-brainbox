@extends('layouts.admin.app')
@section('content')
    @component('components.list')
        @slot('title','Lista de Etiquetas ['. count($tags).']')
        @slot('actions')
            <add-tag-btn></add-tag-btn>
        @endslot
        @slot('filters')@endslot
        @slot('table')
            <list-tags :p_tags="{{ json_encode($tags) }}"></list-tags>
        @endslot
    @endcomponent
    <modal-tag></modal-tag>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready( function () {
            $('#dtTags').DataTable({
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
        #dtTags_filter {
            float: left !important;
        }

        #dtTags_filter input {
            width: 400px;
            outline: 0px solid #aaa;
        }

        #dtTags_length {
            float: right !important;
        }
        #dtTags_length label {
            display:flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush
