<tr>
    <th scope="row">
        <div class="media align-items-center">
            <a href="#" class="avatar rounded-circle mr-3">
                <img alt="Image placeholder" src="{{ $customer->src_logo }}">
            </a>
            <div class="media-body">
                <span class="name mb-0 text-sm">{{ $customer->name }}</span>
                <h6 class="mb-0 text-muted">
                    @if ($customer->contact_name)
                        <i class="far fa-user ml-1"></i> {{ $customer->contact_name }}
                    @endif
                    @if ($customer->contact_telephone)
                        <i class="fas fa-phone-alt ml-1"></i> {{ $customer->contact_telephone }}
                    @endif
                    @if ($customer->contact_email)
                        <i class="far fa-envelope ml-1"></i> {{ $customer->contact_email }}
                    @endif
                </h6>
            </div>
        </div>
    </th>
    <td>{{ $customer->ruc }}</td>
    <td>{{ $customer->present()->currentStatus() }}</td>
    <td class="text-right">
        <a href="{{ route('admin.customers.show',$customer->id) }}">
            <i class="far fa-eye mr-1 text-primary"></i>
        </a>
        <div class="dropdown">
            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <a class="dropdown-item" href="{{route('admin.customers.show',$customer->id)}}">
                    <i class="fa fa-eye text-primary"></i>
                    <span>Ver</span>
                </a>
                <a class="dropdown-item" href="{{route('admin.customers.edit',$customer->id)}}">
                    <i class="ni ni-ruler-pencil text-primary"></i>
                    <span>Editar</span>
                </a>
                <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item" onClick="javascript: return confirm('¿Estas seguro de elimarlo?');">
                        <i class="fas fa-trash-alt text-danger"></i>
                        <span>Eliminar</span>
                    </button>
                </form>
            </div>
        </div>
    </td>
</tr>
