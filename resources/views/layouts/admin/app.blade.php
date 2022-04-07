<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Bsw - Task Manager')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icon_task_manager16.ico') }}" />


    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Argon CSS -->

    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css">
    <meta name="user" content="{{ Auth::user() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <!-- select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" integrity="sha256-FdatTf20PQr/rWg+cAKfl6j4/IY3oohFAJ7gVC3M34E=" crossorigin="anonymous">
    <!-- select2-bootstrap4-theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css"> <!-- for live demo page -->

    <style>

        .select2-selection__arrow {
            display: inline;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            height: 31px !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            padding-left: 0.25rem;
            line-height: calc(0.55rem + .75rem);
            color: #96a4b4;
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow b {
            position: absolute;
            top: 60%;
            left: 50%;
            width: 0;
            height: 0;
            margin-top: -2px;
            margin-left: -4px;
            border-color: #96a4b4 transparent #5a618a00 transparent;
            border-style: solid;
            border-width: 5px 4px 0;
        }
    </style>
</head>
<body>

<!-- Sidenav -->
@include('layouts.admin.sidebard')

<!-- Main content -->
<div class="main-content" id="panel">
    <!-- Topnav -->
    @include('layouts.admin.topnav')
    <!-- Header -->
{{--    @include('layouts.admin.header')--}}
    <!-- Header -->
    <br>
    <!-- Page content -->
    <div class="container-fluid pb-6 ">
        @include('shared._alert')
        <div id="app">
            @yield('content')
        </div>
    </div>
</div>
{{--@include('layouts.admin.footer')--}}


<!-- Argon Scripts -->
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('js/scripts2.js')}}"></script>
<script src="{{asset('js/argon.js')}}"></script>
<script src="{{asset('js/select2.min.js')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js" defer></script>
<script src="https://cdn.bootcss.com/moment.js/2.22.1/moment-with-locales.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<script>
    $(function () {
        $('.custselecttwo').each(function () {
            $(this).select2({
                theme: 'bootstrap4',
            });
        });
    });
</script>
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 12px;
    }
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        height: 31px;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #8898aa;
    }
</style>
@stack('js')
@stack('styles')




</body>
</html>
