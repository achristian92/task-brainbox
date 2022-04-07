@extends('layouts.auth.app')
@section('title', 'Reiniciar clave - bia consultores')
@section('title-form', '¿No puedes ingresar a tu cuenta?')
@section('intro-form', $title)
@section('content')
    @if (session('status'))
        <div style="color: #00b19d">
            {{ session('status') }}
        </div>
    @endif
    <form class="form" method="POST" action="{{$passwordEmailRoute}}">
        @csrf
        <div class="input-wrapper">
            <input  type="email" name="email" id="email" class="email" placeholder="Correo" required value="{{old('email')}}" />
        </div>
        <input type="submit" value="RESTABLECER" class="enviar">
    </form>
@endsection
@section('acceso')
    <p><a href="{{$backLogin}}" style="color: #157297;transition: .2s;text-decoration: none;">Volver al inicio de sesión</a></p>
@endsection

