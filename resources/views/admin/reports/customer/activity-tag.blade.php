<br><br><br><br><br><br><br><br>
<table class="table table-bordered">
    <thead>
    <tr>
        <th></th>
        <th><strong>FECHA</strong></th>
        <th><strong>ETIQUETA | ACTIVIDAD</strong></th>
        <th><strong>ESTIMADO</strong></th>
        <th><strong>REAL</strong></th>
        <th><strong>ESTADO</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($tags as $tag)
        <tr>
            <td style="background-color: #DAEEF3"></td>
            <td style="background-color: #DAEEF3"></td>
            <td style="background-color: #DAEEF3"><strong>{{$tag['tag']}}</strong></td>
            <td style="background-color: #DAEEF3"><strong>{{ $tag['totalEstimatedTime'] }}</strong></td>
            <td style="background-color: #DAEEF3"><strong>{{ $tag['totalRealTime'] }}</strong></td>
            <td style="background-color: #DAEEF3"></td>
        </tr>
        @foreach($tag['activities'] as $activity)
            <tr>
                <td></td>
                <td>{{$activity['startDateFormat']}}</td>
                <td>{{$activity['name']}} {{ !$activity['isPlanned'] ? '(Nuevo)' : ''  }} </td>
                <td>{{$activity['estimatedTime']}}</td>
                <td>{{$activity['realTime']}}</td>
                <td style="background-color: {{$activity['color']}};">{{$activity['statusName']}}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td></td>
        <td  colspan="2"><strong>Total:</strong></td>
        <td>{{$totalEstimatedTime}}</td>
        <td>{{$totalRealTime}}</td>
    </tr>
    </tfoot>
</table>
