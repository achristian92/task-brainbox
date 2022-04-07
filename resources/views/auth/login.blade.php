@extends('layouts.auth.app')
@section('title-form', 'Iniciar Sesión')
@section('intro-form', 'Usuario')
@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="input-wrapper">
            <input type="email"
                   name="email"
                   id="email"
                   class="email"
                   placeholder="Correo"
                   required value="{{old('email')}}" />
        </div>
        <div class="input-wrapper">
            <input type="password"
                   name="password"
                   class="clave"
                   placeholder="Contraseña"
                   required />
        </div>
        <input type="submit" value="INGRESAR" class="enviar">
    </form>
@endsection
@section('acceso')
    <p><a href="{{$resetPassword}}" style="color: #157297;transition: .2s;text-decoration: none;">¿Olvidaste tu contraseña?</a></p>
@endsection

