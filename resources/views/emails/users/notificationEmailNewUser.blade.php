@extends('emails.app')
@section('content')
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
        <br>
        <tr>
            <td width="100">&nbsp;</td>
            <td width="400" align="center">
                <div>
                    <div align='center' >
                        <p >¡Hola <strong style="color: #1ab394">{{$data['name']}}!</strong>,
                            El equipo de <b>{{ $setting->project }}</b> te da la bienvenida.</p>
                    </div>
                    <div align='left' >
                        <p><b style="color: #00525d;">Tus credenciales</b></p>
                        <ul>
                            <li>Correo: {{$data['email']}}</li>
                            <li>Contraseña: {{$data['password']}}</li>
                        </ul>
                    </div>
                </div>
            </td>
            <td width="100">&nbsp;</td>
        </tr>
        <tr>
            <td width="200">&nbsp;</td>
            <td width="200" align="center" style="padding-top:15px;">
                <table cellpadding="0" cellspacing="0" border="0" align="center" width="200" height="50">
                    <tr>
                        <td bgcolor="#00525d" align="center" style="border-radius:4px;" width="200" height="50">
                            <div>
                                <div align='center'>
                                    <a target='_blank' href="{{$data['urlToLogin']}}" class='link2'>INGRESA AHORA </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="200">&nbsp;</td>
        </tr>
    </table>

@stop


