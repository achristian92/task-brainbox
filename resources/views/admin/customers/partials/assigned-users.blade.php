<ul class="list-group list-group-flush small border-0">
    <h4>Lista de usuarios</h4>
    @foreach($users AS $user)
        <li class="list-group-item">{{ $user->full_name }}</li>
    @endforeach
</ul>
