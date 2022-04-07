
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title', 'Bsw - Task Manager')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icon_task_manager16.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="{{asset('css/auth.css')}}" type="text/css"/>

</head>
<style>
    a {
        color: white;
        transition: background-color .5s;
    }
    a:hover {
        color: #021d5f;
    }
</style>
<body>
<div class="main-container">
    <!-- Sidebar Access -->
    <aside class="sidebar-large">
        <div class="user-access">
            <div class="user-access-header">
{{--                <img src="{{ $setting->url_logo }}" alt="logo_principal" style="object-fit: contain;"  width="250" height="100">--}}
                <img src="{{ asset('img/BSW_TASK MANAGER_LOGO.png') }}" alt="logo_principal" style="object-fit: contain;"  width="250" height="100">
            </div>
            <div class="user-access-form">
                <p class="intro-title">@yield('title-form')</p>
                <p class="intro-summary">@yield('intro-form')</p>
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul style="color: red;">
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
            <div class="user-access-footer">
                @yield('acceso')
            </div>
        </div>
    </aside>
    <!-- Content Slideshow    -->
    <div class="carrousel-wrapper carrousel-login" id="slider-login">
        <div class="carrousel-slide active"  style="background: url({{asset('img/foto12.jpg')}}) center center no-repeat;">
        </div>
    </div>
</div>
</body>
</html>
