@extends('layouts.admin.app')
@section('content')
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
                                Documentos de {{ $user->full_name }}
                            </h3>
                        </div>
                    </div>
                </div>
                <!-- Light table -->
                 <div class="table-responsive">
                     <table class="table align-items-center table-flush table-sm">
                         <thead class="thead-light">
                         <tr>
                             <th>Descripción</th>
                         </tr>
                         </thead>
                         <tbody>
                         @foreach($documents as $document)
                             <tr>
                                 <td>
                                     {{ $document->name }} <br>
                                     <small> {{ $document->url_file }} </small> <br>
                                     <small>ID: {{ $document->id }} | Fecha: {{ $document->created_at }} </small>
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
