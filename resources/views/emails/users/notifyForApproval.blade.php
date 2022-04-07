@extends('emails.app')
@section('content')
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
        <tr>
            <td width="100">&nbsp;</td>
            <td width="400" align="center">
                <div>
                    <div align='center' >
                        <p>¡Hola <strong style="color: #1ab394">{{$user->name}}!</strong>,
                            Ayer <b>{{$yesterday}}</b> se completaron actividades fuera de fecha,
                            requiere tu aprobación o rechazo.</p>
                    </div>
                </div>
            </td>
            <td width="100">&nbsp;</td>
        </tr>
    </table>
    <br>
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
        <thead>
        <tr>
            <th scope="col">COLABORADOR</th>
            <th scope="col"># ACT</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $user)
            <tr>
                <td>{{$user['user']}}</td>
                <td align="right">{{$user['qty']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop


