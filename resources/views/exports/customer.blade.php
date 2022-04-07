<table class="table table-bordered">
    <thead>
    <tr>
        <th><strong>EMPRESA</strong></th>
        <th><strong>RUC</strong></th>
        <th><strong>DIRECCION</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td>{{ $customer['name'] }}</td>
            <td>{{ $customer['ruc'] }}</td>
            <td>{{ $customer['address'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
