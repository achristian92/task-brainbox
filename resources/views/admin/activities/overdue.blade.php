@extends('layouts.admin.app')
@section('content')
    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <span class="text-danger h3">Actividades vencidas</span>
                        </div>
                        <div class="col-md-6 text-right">
                            @include('shared.dropdownListMonths',['router_link' => 'admin.activities.overdue.index'])
                        </div>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($activitiesOverdue as $date => $activities)
                    <li class="list-group-item h4 font-weight-bold">{{$date}}</li>
                        @foreach($activities as $activity)
                            <li class="list-group-item ml-4">
                                <span class="h5">{{$activity['name']}}</span> <br>
                                <span class="h6 text-muted">
                                <i class="far fa-user mr-1"></i>
                                {{$activity['user']}} |
                                <i class="far fa-building mr-1 ml-1"></i>
                                {{$activity['customer']}} |
                                <i class="far fa-clock mr-1 ml-1"></i>
                                {{$activity['estimatedHours']}} hrs |
                                <i class="far fa-flag mr-1 ml-1"></i>
                                {{$activity['is_priority']}}
                                </span>
                            </li>
                        @endforeach
                    @empty
                    <li class="list-group-item h4 font-weight-bold">No hay actividades vencidas.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
