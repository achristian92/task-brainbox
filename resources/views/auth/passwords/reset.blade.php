@extends('layouts.auth.app')
@section('title', 'Cambio de clave - bia consultores')
@section('title-form', 'Ingresa a tu cuenta de nuevo')
@section('intro-form', $title)
@section('content')
    <form class="form" method="POST" action="{{$passwordUpdateRoute}}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="input-wrapper">
            <input type="email" name="email" id="email" class="email" placeholder="Correo" required value="{{old('email')}}" />
        </div>
        <div class="input-wrapper">
            <input type="password" name="password"  class="clave" placeholder="Nueva contraseña" required />
        </div>
        <div class="input-wrapper">
            <input type="password" name="password_confirmation"  class="clave" placeholder="Confirmar contraseña" required />
        </div>
        <input type="submit" value="RESTABLECER" class="enviar">
    </form>
@endsection
@section('acceso')
    <p><a href="{{$backLogin}}" style="color: #157297;transition: .2s;text-decoration: none;">Volver al inicio de sesión</a></p>
@endsection
