<br><br><br><br><br><br><br><br>
<table class="table table-bordered">
    <thead>
    <tr>
        <th></th>
        <th><strong>FECHA</strong></th>
        <th><strong>CLIENTE </strong></th>
        <th><strong>ACTIVIDAD</strong></th>
        <th><strong>TIEMPO</strong></th>
        <th><strong>ESTADO</strong></th>
        <th><strong>ETIQUETAS</strong></th>
        <th><strong>DESCRIPCIÓN</strong></th>
    </tr>
    </thead>
    <tbody>
        @foreach($data as $act)
            <tr>
                <td></td>
                <td>{{ $act['startDate'] }}</td>
                <td>{{ $act['customer'] }}</td>
                <td>{{ $act['name'] }}</td>
                <td>{{ $act['estimatedTime'] }}</td>
                <td>{{ $act['statusName'] }}</td>
                <td>{{ $act['tags'] }}</td>
                <td>{{ $act['description'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
