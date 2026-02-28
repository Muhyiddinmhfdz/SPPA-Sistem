<form id="form-user-create" action="{{ route('master.user.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label required">Nama Lengkap</label>
            <input type="text" name="name" class="form-control form-control-solid" placeholder="Masukkan nama lengkap" required>
        </div>
        <div class="col-md-6">
            <label class="form-label required">Username</label>
            <input type="text" name="username" class="form-control form-control-solid" placeholder="Masukkan username" required>
        </div>
        <div class="col-md-6">
            <label class="form-label required">Email</label>
            <input type="email" name="email" class="form-control form-control-solid" placeholder="Masukkan email" required>
        </div>
        <div class="col-md-6">
            <label class="form-label required">Role</label>
            <select name="role_id" class="form-select form-select-solid" required>
                <option value="">Pilih Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label required">Password</label>
            <input type="password" name="password" class="form-control form-control-solid" placeholder="Masukkan password" required>
        </div>
        <div class="col-md-6">
            <label class="form-label required">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control form-control-solid" placeholder="Konfirmasi password" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Status</label>
            <div class="form-check form-switch form-check-solid">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                <label class="form-check-label fw-semibold text-gray-800">Aktif</label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
            <span class="indicator-label">Simpan</span>
            <span class="indicator-progress">
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </div>
    </div>
</form>
