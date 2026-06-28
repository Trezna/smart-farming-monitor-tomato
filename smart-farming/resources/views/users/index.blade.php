@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('page-subtitle', 'Kelola akun admin dan viewer sistem')

@section('content')

{{-- Header Actions --}}
<div style="display:flex;justify-content:flex-end;margin-bottom:20px;">
    <button class="btn btn-primary" onclick="openModal('addModal')">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah User
    </button>
</div>

{{-- Users Table --}}
<div class="card">
    <div class="card-header">Daftar User ({{ $users->total() }} total)</div>
    <div class="card-body" style="padding:0 0 18px;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NAMA</th>
                        <th>EMAIL</th>
                        <th>ROLE</th>
                        <th>TERDAFTAR</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                    <tr>
                        <td style="color:#9ca3af;font-size:0.78rem;">{{ ($users->currentPage()-1)*15 + $i + 1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="avatar" style="width:34px;height:34px;font-size:0.8rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span style="font-weight:600;">{{ $user->name }}</span>
                                @if($user->id === auth()->id())
                                <span class="badge badge-green" style="font-size:0.68rem;">Kamu</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-viewer' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td style="font-size:0.82rem;color:#6b7280;">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <button class="btn btn-secondary btn-sm"
                                        onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->role }}')">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                                      onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:#9ca3af;">Tidak ada user</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div style="padding:16px 22px;border-top:1px solid #f0f0f0;display:flex;justify-content:flex-end;">
            <div class="pagination">
                @if(!$users->onFirstPage())
                    <a class="page-link" href="{{ $users->previousPageUrl() }}">← Prev</a>
                @endif
                @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    <a class="page-link {{ $page == $users->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                @endforeach
                @if($users->hasMorePages())
                    <a class="page-link" href="{{ $users->nextPageUrl() }}">Next →</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Add User Modal --}}
<div class="modal-overlay" id="addModal">
    <div class="modal-box">
        <div class="modal-title">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#3b6d11" stroke-width="2" style="display:inline;margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah User Baru
        </div>
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="add_name">Nama Lengkap *</label>
                <input type="text" id="add_name" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       value="{{ old('name') }}" placeholder="Nama lengkap" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="add_email">Email *</label>
                <input type="email" id="add_email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       value="{{ old('email') }}" placeholder="email@example.com" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="add_role">Role *</label>
                <select id="add_role" name="role" class="form-control" required>
                    <option value="viewer" {{ old('role')=='viewer'?'selected':'' }}>Viewer</option>
                    <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="add_password">Password * (min. 6 karakter)</label>
                <input type="password" id="add_password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="••••••••" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="add_password_confirmation">Konfirmasi Password *</label>
                <input type="password" id="add_password_confirmation" name="password_confirmation"
                       class="form-control" placeholder="••••••••" required>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary">Simpan User</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit User Modal --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <div class="modal-title">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#3b6d11" stroke-width="2" style="display:inline;margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit User
        </div>
        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label" for="edit_name">Nama Lengkap *</label>
                <input type="text" id="edit_name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_email">Email *</label>
                <input type="email" id="edit_email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_role">Role *</label>
                <select id="edit_role" name="role" class="form-control" required>
                    <option value="viewer">Viewer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_password">Password Baru (kosongkan jika tidak diubah)</label>
                <input type="password" id="edit_password" name="password" class="form-control" placeholder="••••••••">
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" id="edit_password_confirmation" name="password_confirmation"
                       class="form-control" placeholder="••••••••">
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary">Update User</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('active');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}
function openEditModal(id, name, email, role) {
    document.getElementById('editForm').action = '/users/' + id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_password').value = '';
    document.getElementById('edit_password_confirmation').value = '';
    openModal('editModal');
}
// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('active');
    });
});
// Auto-open add modal if validation errors
@if($errors->any() && old('name'))
openModal('addModal');
@endif
</script>

@endsection
