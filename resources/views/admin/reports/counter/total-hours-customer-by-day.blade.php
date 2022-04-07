<br><br><br><br><br><br><br>
<table>
    <thead>
    <tr>
        <th></th>
        <th>CLIENTES \ DIAS DEL MES</th>
        @for($i=0; $i<count($dates); $i++)
            <th>{{ (int)$i+1 }}</th>
        @endfor
        <th>TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $customer)
        <tr>
            <td></td>
            <td>{{$customer['customer']}}</td>
            @foreach($customer['dates'] as $value)
                <td>{{$value}}</td>
            @endforeach
            <td align="right"><b>{{$customer['total']}}</b></td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td align="right"><strong>TOTAL DE HORAS DIARIAS</strong></td>
            @foreach($total as $value)
                <td align="right"><strong>{{$value}}</strong></td>
            @endforeach
        </tr>
    </tfoot>
</table>
