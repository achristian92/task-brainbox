    <tr>
        <th scope="row">
            <div class="media align-items-center">
                <a href="#" class="avatar rounded-circle mr-3">
                    <img alt="Image placeholder" src="{{$user['urlImage']}}">
                </a>
                <div class="media-body">
                    <span class="name mb-0 text-sm">{{$user['fullNameShort']}}</span>
                    <h6 class="mb-0 text-muted">{{$user['lastLogin']}}</h6>
                </div>
            </div>
        </th>
        <td>
            {{$user['email']}}
        </td>
        <td>
            {{$user['roles']}}
        </td>
        <td>
            @include('shared._status',['status' => $user['isActive']])
        </td>
        <td class="text-right">
            <div class="dropdown">
                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" href="{{$user['urlToCredentials']}}">
                        <i class="ni ni-send text-primary"></i>
                        <span>Enviar credenciales</span>
                    </a>
                    <a class="dropdown-item" href="{{$user['urlToEdit']}}">
                        <i class="ni ni-ruler-pencil text-primary"></i>
                        <span>Editar</span>
                    </a>
                    @unless ( Illuminate\Support\Facades\Auth::id() == $user['id'])
                        @if($user['isActive'])
                            <a class="dropdown-item" href="{{$user['urlToDesactive']}}">
                                <i class="ni ni-button-power text-danger"></i>
                                <span>Desactivar</span>
                            </a>
                        @else
                            <a class="dropdown-item" href="{{$user['urlToActive']}}">
                                <i class="ni ni-button-power text-success"></i>
                                <span>Activar</span>
                            </a>
                        @endif
                        <a class="dropdown-item" href="{{$user['urlToDestroy']}}">
                            <i class="fas fa-trash text-danger"></i>
                            <span>Eliminar</span>
                        </a>
                    @endif
                    @if (Auth::user()->isSuperAdmin())
                        <a class="dropdown-item" href="{{ route('admin.users.documents',$user['id']) }}">
                            <i class="ni ni-folder-17 text-primary"></i>
                            <span>Documentos</span>
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.users.history',$user['id']) }}">
                            <i class="ni ni-collection text-primary"></i>
                            <span>Historial</span>
                        </a>
                    @endif
                </div>
            </div>
        </td>
    </tr>

