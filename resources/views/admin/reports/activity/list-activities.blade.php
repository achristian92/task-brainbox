<br><br><br><br><br><br><br><br>
<table class="table table-bordered">
    <thead>
    <tr>
        <th></th>
        <th><strong>FECHA</strong></th>
        <th><strong>COLABORADOR</strong></th>
        <th><strong>ACTIVIDAD</strong></th>
        <th><strong>CLIENTE</strong></th>
        <th><strong>ESTIMADO</strong></th>
        <th><strong>REAL</strong></th>
        <th><strong>ESTADO</strong></th>
        <th><strong>ETIQUETA</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($activities as $activity)
        <tr>
            <td></td>
            <td>{{$activity['startDate']}}</td>
            <td>{{$activity['counter']}}</td>
            <td>{{$activity['name']}} {{ !$activity['isPlanned'] ? '(Nuevo)' : ''  }} </td>
            <td>{{$activity['customer']}}</td>
            <td>{{$activity['estimatedTime']}}</td>
            <td>{{$activity['realTime']}}</td>
            <td style="background-color: {{$activity['color']}};">{{$activity['statusName']}}</td>
            <td>{{$activity['tag']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
