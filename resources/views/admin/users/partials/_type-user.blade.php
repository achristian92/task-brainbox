<div class="row">
    <div class="col">
        <a href="{{route('admin.users.index','q=counters')}}" class="btn {{request('q') == "counters" ? 'btn-primary' : 'btn-secondary'}}" type="button">Colaboradores</a>
        @if(Illuminate\Support\Facades\Auth::check())<a href="{{route('admin.users.index','q=supervisors')}}" class="btn {{request('q') == "supervisors" ? 'btn-primary' : 'btn-secondary'}}" type="button">Supervisores</a>@endif
        @if(Illuminate\Support\Facades\Auth::check())<a href="{{route('admin.users.index','q=admins')}}" class="btn {{request('q') == "admins" ? 'btn-primary' : 'btn-secondary'}}" type="button">Admin</a>@endif
    </div>
</div>
