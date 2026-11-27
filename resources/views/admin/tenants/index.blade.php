@extends('admin.layouts.app')

@section('title', 'Manajemen Tenant')
@section('page_title', 'Manajemen Tenant Booth')

@section('content')
  <div class="toolbar">
    <form method="GET" action="{{ route('admin.tenants.index') }}" class="filter-form">
      <input type="text" name="q" value="{{ $search }}" placeholder="Cari usaha, pemilik, booth..." class="form-control" style="width: 240px;">
      <select name="status" class="form-control" style="width: 160px;">
        <option value="">Semua Status</option>
        <option value="pending" @selected($status === 'pending')>Pending</option>
        <option value="approved" @selected($status === 'approved')>Disetujui</option>
        <option value="rejected" @selected($status === 'rejected')>Ditolak</option>
      </select>
      <button type="submit" class="btn btn-outline">Filter</button>
    </form>

    <button type="button" class="btn btn-crimson" data-open-dialog="modal-add-tenant">
      + Tambah Tenant
    </button>
  </div>

  <div class="panel-card">
    <div class="table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Nama Usaha</th>
            <th>Pemilik</th>
            <th>Kategori</th>
            <th>Booth & Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($tenants as $tenant)
            <tr>
              <td>
                <strong>{{ $tenant->business_name }}</strong>
                @if ($tenant->notes)
                  <div style="font-size: 12px; color: var(--text-muted);">{{ $tenant->notes }}</div>
                @endif
              </td>
              <td>
                <div>{{ $tenant->owner_name }}</div>
                <div style="font-size: 12px; color: var(--text-muted);">{{ $tenant->phone }}</div>
              </td>
              <td>{{ $tenant->category }}</td>
              <td>
                <form method="POST" action="{{ route('admin.tenants.status', $tenant) }}" style="display: flex; gap: 6px; align-items: center;">
                  @csrf
                  @method('PATCH')
                  <input type="text" name="booth_code" value="{{ $tenant->booth_code }}" placeholder="Booth" class="form-control form-control-sm" style="width: 78px;">
                  <select name="status" class="form-control form-control-sm">
                    <option value="pending" @selected($tenant->status === 'pending')>Pending</option>
                    <option value="approved" @selected($tenant->status === 'approved')>Disetujui</option>
                    <option value="rejected" @selected($tenant->status === 'rejected')>Ditolak</option>
                  </select>
                  <button type="submit" class="btn btn-outline btn-sm">Simpan</button>
                </form>
              </td>
              <td>
                <form method="POST" action="{{ route('admin.tenants.destroy', $tenant) }}" onsubmit="return confirm('Hapus tenant {{ $tenant->business_name }}?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger-ghost">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 28px;">Tidak ada data tenant.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($tenants->hasPages())
      <div class="pagination-wrap">{{ $tenants->links() }}</div>
    @endif
  </div>

  <dialog id="modal-add-tenant" class="admin-dialog">
    <div class="dialog-head">
      <h3>Tambah Tenant Booth</h3>
      <button type="button" class="btn btn-outline btn-sm" data-close-dialog>Tutup</button>
    </div>
    <form method="POST" action="{{ route('admin.tenants.store') }}" class="dialog-body">
      @csrf
      <div class="form-grid">
        <div class="form-group">
          <label>Nama Usaha</label>
          <input type="text" name="business_name" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Nama Pemilik</label>
          <input type="text" name="owner_name" class="form-control" required>
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label>No. Telepon / WA</label>
          <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" class="form-control">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label>Kategori</label>
          <select name="category" class="form-control">
            <option value="Kuliner">Kuliner</option>
            <option value="Kriya & Seni">Kriya &amp; Seni</option>
            <option value="Edukasi">Edukasi</option>
            <option value="Fashion">Fashion</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>
        <div class="form-group">
          <label>Nomor Booth</label>
          <input type="text" name="booth_code" class="form-control" placeholder="Mis. A-02">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label>Status</label>
          <select name="status" class="form-control">
            <option value="pending">Pending</option>
            <option value="approved">Disetujui</option>
            <option value="rejected">Ditolak</option>
          </select>
        </div>
        <div class="form-group">
          <label>Catatan</label>
          <input type="text" name="notes" class="form-control" placeholder="Opsional">
        </div>
      </div>
      <div class="dialog-foot">
        <button type="button" class="btn btn-outline" data-close-dialog>Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Tenant</button>
      </div>
    </form>
  </dialog>
@endsection
