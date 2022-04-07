@extends('emails.app')
@section('content')
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
        <tr>
            <td width="100">&nbsp;</td>
            <td width="400" align="center">
                <div>
                    <div align='center' >
                        <p>¡Hola <strong style="color: #1ab394">{{$data['user']}}!</strong>,
                            mañana <b>{{ $data['deadline'] }}</b> es la fecha límite para realizar las siguientes actividades:</p>
                    </div>
                    <div align='left' >
                        <p><b style="color: #00525d;">Detalle</b></p>
                        <ul>
                            @foreach($data['activities'] as $activity)
                                <li>
                                    {{$activity['activity']}} <br>
                                    <span style="font-weight:200; font-size: 12px;">
                                        Cliente: {{ $activity['customer'] }} |
                                        Tiempo: {{ $activity['time'] }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </td>
            <td width="100">&nbsp;</td>
        </tr>
    </table>
@stop
