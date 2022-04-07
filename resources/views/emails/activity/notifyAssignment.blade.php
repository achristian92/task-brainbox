@extends('emails.app')
@section('content')
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
        <br>
        <tr>
            <td width="100">&nbsp;</td>
            <td width="400" align="center">
                <div>
                    <div align='center' >
                        <p >¡Hola <strong style="color: #1ab394">{{$data['toUser']}}!</strong>,
                            El equipo de <b>{{ $setting['project'] }}</b> te comunica que te asignaron una nueva actividad.</p>
                    </div>
                    <div align='left' >
                        <p><b style="color: #00525d;">Detalle</b></p>
                        <ul>
                            <li><span style="font-weight:300; font-size: 14px;">Actividad:</span> {{$data['activity']}}</li>
                            <li><span style="font-weight:300; font-size: 14px;">Cliente:</span> {{$data['customer']}}</li>
                            <li><span style="font-weight:300; font-size: 14px;">Tiempo:</span> {{$data['timeEstimate']}}</li>
                            <li><span style="font-weight:300; font-size: 14px;">Fecha:</span> {{$data['date']}}</li>
                            <li><span style="font-weight:300; font-size: 14px;">Estado:</span> {{$data['status']}}</li>
                        </ul>
                    </div>
                </div>
            </td>
            <td width="100">&nbsp;</td>
        </tr>
    </table>

@stop


