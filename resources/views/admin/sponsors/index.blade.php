@extends('admin.layouts.app')

@section('title', 'Manajemen Sponsor')
@section('page_title', 'Manajemen Sponsor & Kemitraan')

@section('content')
  <div class="toolbar">
    <form method="GET" action="{{ route('admin.sponsors.index') }}" class="filter-form">
      <input type="text" name="q" value="{{ $search }}" placeholder="Cari instansi atau PIC..." class="form-control" style="width: 240px;">
      <select name="tier" class="form-control" style="width: 160px;">
        <option value="">Semua Tier</option>
        <option value="Platinum" @selected($tier === 'Platinum')>Platinum</option>
        <option value="Gold" @selected($tier === 'Gold')>Gold</option>
        <option value="Silver" @selected($tier === 'Silver')>Silver</option>
        <option value="Partner" @selected($tier === 'Partner')>Partner</option>
      </select>
      <button type="submit" class="btn btn-outline">Filter</button>
    </form>

    <button type="button" class="btn btn-crimson" data-open-dialog="modal-add-sponsor">
      + Tambah Sponsor
    </button>
  </div>

  <div class="panel-card">
    <div class="table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Instansi / Perusahaan</th>
            <th>PIC</th>
            <th>Tier</th>
            <th>Kontribusi</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($sponsors as $sponsor)
            <tr>
              <td>
                <strong>{{ $sponsor->company_name }}</strong>
                @if ($sponsor->notes)
                  <div style="font-size: 12px; color: var(--text-muted);">{{ $sponsor->notes }}</div>
                @endif
              </td>
              <td>
                <div>{{ $sponsor->contact_person }}</div>
                <div style="font-size: 12px; color: var(--text-muted);">{{ $sponsor->phone }}</div>
              </td>
              <td><span class="badge badge-info">{{ $sponsor->tier }}</span></td>
              <td>Rp {{ number_format($sponsor->contribution_amount, 0, ',', '.') }}</td>
              <td>
                <form method="POST" action="{{ route('admin.sponsors.status', $sponsor) }}">
                  @csrf
                  @method('PATCH')
                  <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="prospect" @selected($sponsor->status === 'prospect')>Prospek</option>
                    <option value="confirmed" @selected($sponsor->status === 'confirmed')>Confirmed</option>
                    <option value="declined" @selected($sponsor->status === 'declined')>Declined</option>
                  </select>
                </form>
              </td>
              <td>
                <form method="POST" action="{{ route('admin.sponsors.destroy', $sponsor) }}" onsubmit="return confirm('Hapus sponsor {{ $sponsor->company_name }}?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger-ghost">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 28px;">Tidak ada data sponsor.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($sponsors->hasPages())
      <div class="pagination-wrap">{{ $sponsors->links() }}</div>
    @endif
  </div>

  <dialog id="modal-add-sponsor" class="admin-dialog">
    <div class="dialog-head">
      <h3>Tambah Sponsor</h3>
      <button type="button" class="btn btn-outline btn-sm" data-close-dialog>Tutup</button>
    </div>
    <form method="POST" action="{{ route('admin.sponsors.store') }}" class="dialog-body">
      @csrf
      <div class="form-group">
        <label>Nama Instansi / Perusahaan</label>
        <input type="text" name="company_name" class="form-control" required>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label>Contact Person (PIC)</label>
          <input type="text" name="contact_person" class="form-control" required>
        </div>
        <div class="form-group">
          <label>No. Telepon / WA</label>
          <input type="text" name="phone" class="form-control" required>
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" class="form-control">
        </div>
        <div class="form-group">
          <label>Tier Sponsor</label>
          <select name="tier" class="form-control">
            <option value="Platinum">Platinum</option>
            <option value="Gold">Gold</option>
            <option value="Silver">Silver</option>
            <option value="Partner">Partner</option>
          </select>
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label>Nilai Kontribusi (Rp)</label>
          <input type="number" name="contribution_amount" min="0" value="5000000" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="status" class="form-control">
            <option value="prospect">Prospek</option>
            <option value="confirmed">Confirmed</option>
            <option value="declined">Declined</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label>Catatan</label>
        <input type="text" name="notes" class="form-control" placeholder="Opsional">
      </div>
      <div class="dialog-foot">
        <button type="button" class="btn btn-outline" data-close-dialog>Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Sponsor</button>
      </div>
    </form>
  </dialog>
@endsection
