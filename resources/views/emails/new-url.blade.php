@extends('emails.app')
@section('content')
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
        <br>
        <tr>
            <td width="100">&nbsp;</td>
            <td width="400" align="center">
                <div>
                    <div align='center' >
                        <p >¡Hola <strong style="color: #1ab394">{{ $user->name }}!</strong>,
                            El equipo de <b> JGA TEAM </b> te informa que la nueva dirección para acceder al sistema control de actividades es la siguiente:</p>
                        <p><b>https://brainbox.pe/control-de-actividades/login</b></p>

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
                                    <a target='_blank' href="https://brainbox.pe/control-de-actividades/login" class='link2'>INGRESA AHORA </a>
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


