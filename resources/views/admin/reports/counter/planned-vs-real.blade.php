<br><br><br><br><br><br><br><br>
<table class="table table-bordered">
    <thead>
    <tr>
        <th></th>
        <th><strong>FECHA</strong></th>
        <th><strong>CLIENTE | ACTIVIDAD</strong></th>
        <th><strong>ESTIMADO</strong></th>
        <th><strong>REAL</strong></th>
        <th><strong>ESTADO</strong></th>
        <th><strong>ETIQUETA</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td></td>
            <td></td>
            <td><strong>{{$customer['customer']}}</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($customer['activities'] as $activity)
            <tr>
                <td></td>
                <td>{{$activity['startDate']}}</td>
                <td>{{$activity['name']}} {{ !$activity['isPlanned'] ? '(Nuevo)' : ''  }} </td>
                <td>{{$activity['estimatedTime']}}</td>
                <td>{{$activity['realTime']}}</td>
                <td style="background-color: {{$activity['color']}};">{{$activity['statusName']}}</td>
                <td>{{$activity['tag']}}</td>
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
