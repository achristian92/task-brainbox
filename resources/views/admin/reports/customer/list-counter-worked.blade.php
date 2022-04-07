<br><br><br><br><br><br><br>
<table>
    <thead>
    <tr>
        <th></th>
        <th>COLABORADORES \ DIAS DEL MES</th>
        @for($i=0; $i<count($dates); $i++)
            <th>{{ (int)$i+1 }}</th>
        @endfor
        <th>TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($counters as $counter)
        <tr>
            <td></td>
            <td>{{$counter['counter']}}</td>
            @foreach($counter['dates'] as $value)
                <td>{{$value}}</td>
            @endforeach
            <td align="right"><strong>{{$counter['total']}}</strong></td>
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
