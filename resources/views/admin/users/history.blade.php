@extends('layouts.admin.app')
@section('content')
    <div class="pull-right">
        <!-- search form -->
        <form action="{{ route('admin.users.history',$user->id) }}" method="get" id="admin-search">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Buscar..." value="{{ request()->input('q') }}">
                <span class="input-group-btn">
                <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i> Filtrar </button>
            </span>
            </div>
        </form>
        <!-- /.search form -->
    </div>

    <br>

    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">
                                <a href="{{ route('admin.users.index') }}">
                                    <i class="fas fa-arrow-left mr-2 ml-1"></i>
                                </a>
                                Historial de {{ $user->full_name }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-sm">
                        <thead class="thead-light">
                        <tr>
                            <th>Descripcion</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($histories as $history)
                            <tr>
                                <td>
                                    {{ $history->description }} <br>
                                    <small>ID:{{ $history->id }} | Fecha:{{ $history->created_at }} </small>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
