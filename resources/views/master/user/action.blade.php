@can(['auth'])
    <td class="text-gray-700 fw-bold">{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->role_names }}</td>
</tr>
<tr>
    <td>
        <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
            {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
        </span>
    </td>
    <td>{{ $user->created_at->format('d M Y H:i') }}</td>
    <td>
        <a href="{{ route('master.user.edit', $user->id }}" class="btn btn-icon btn-active-warning btn-sm me-1" data-bs-toggle="modal" data-target="#modal-user-edit">
            <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
        </a>
        <form action="{{ route('master.user.destroy', $user->id }}" method="POST" onsubmit="return confirm('Hapus user ini?', event, $user->id)">
            @csrf
            <button type="submit" class="btn btn-icon btn-active-danger btn-sm me-1">
                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span></i>
            </button>
        </form>
    </td>
</tr>

