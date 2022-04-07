<tr>
    <td>
        <span class="name mb-0 text-sm">
            <i class="ni ni-tag mr-2" style="color: {{$tag['color']}}"></i>
            {{$tag['name']}}
        </span>
    </td>
    <td>
        @include('shared._status',['status' => $tag['state']])
    </td>
    <td class="table-actions">
        <a href="#" data-id="{{$tag['id']}}" class="table-action btn-edit-tag" data-toggle="tooltip" data-original-title="Editar etiqueta">
            <i class="far fa-edit"></i>
        </a>
        <a href="#!"
           class="table-action table-action-delete"
           data-toggle="tooltip"
           data-original-title="Eliminar etiqueta"
           data-confirm="Estas seguro de eliminar este registro?"
           >
            <i class="fas fa-trash"></i>
        </a>
        <form action="{{ route('admin.tags.destroy', $tag['id']) }}" id="form-delete-tag" method="POST" style="display: none;">
            {{ csrf_field() }}
            @method('DELETE')
        </form>
    </td>
</tr>
