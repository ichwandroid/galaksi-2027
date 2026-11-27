@extends('admin.layouts.app')

@section('title', 'Manajemen Tiket')
@section('page_title', 'Manajemen Tiket')

@section('content')
  <div class="toolbar">
    <form method="GET" action="{{ route('admin.tickets.index') }}" class="filter-form">
      <input type="text" name="q" value="{{ $search }}" placeholder="Cari kode, nama, email..." class="form-control" style="width: 240px;">
      <select name="status" class="form-control" style="width: 160px;">
        <option value="">Semua Status</option>
        <option value="pending" @selected($status === 'pending')>Pending</option>
        <option value="confirmed" @selected($status === 'confirmed')>Terkonfirmasi</option>
        <option value="cancelled" @selected($status === 'cancelled')>Dibatalkan</option>
      </select>
      <button type="submit" class="btn btn-outline">Filter</button>
    </form>

    <button type="button" class="btn btn-crimson" data-open-dialog="modal-add-ticket">
      + Tambah Pesanan
    </button>
  </div>

  <div class="panel-card">
    <div class="table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Kode</th>
            <th>Pembeli</th>
            <th>Kategori</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($orders as $order)
            <tr>
              <td><strong>{{ $order->order_code }}</strong></td>
              <td>
                <div>{{ $order->buyer_name }}</div>
                <div style="font-size: 12px; color: var(--text-muted);">{{ $order->buyer_email }} · {{ $order->buyer_phone }}</div>
              </td>
              <td>{{ $order->category }}</td>
              <td>{{ $order->quantity }}</td>
              <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
              <td>
                <form method="POST" action="{{ route('admin.tickets.status', $order) }}" style="display: flex; gap: 6px; align-items: center;">
                  @csrf
                  @method('PATCH')
                  <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="pending" @selected($order->status === 'pending')>Pending</option>
                    <option value="confirmed" @selected($order->status === 'confirmed')>Terkonfirmasi</option>
                    <option value="cancelled" @selected($order->status === 'cancelled')>Dibatalkan</option>
                  </select>
                </form>
              </td>
              <td>
                <form method="POST" action="{{ route('admin.tickets.destroy', $order) }}" onsubmit="return confirm('Hapus pesanan {{ $order->order_code }}?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger-ghost">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 28px;">Tidak ada data pesanan tiket.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($orders->hasPages())
      <div class="pagination-wrap">{{ $orders->links() }}</div>
    @endif
  </div>

  <dialog id="modal-add-ticket" class="admin-dialog">
    <div class="dialog-head">
      <h3>Tambah Pesanan Tiket</h3>
      <button type="button" class="btn btn-outline btn-sm" data-close-dialog>Tutup</button>
    </div>
    <form method="POST" action="{{ route('admin.tickets.store') }}" class="dialog-body">
      @csrf
      <div class="form-group">
        <label>Nama Pembeli</label>
        <input type="text" name="buyer_name" class="form-control" required>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="buyer_email" class="form-control" required>
        </div>
        <div class="form-group">
          <label>No. Telepon / WA</label>
          <input type="text" name="buyer_phone" class="form-control" required>
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label>Kategori</label>
          <select name="category" class="form-control">
            <option value="Reguler">Reguler</option>
            <option value="VIP">VIP</option>
            <option value="Keluarga">Keluarga</option>
          </select>
        </div>
        <div class="form-group">
          <label>Jumlah Tiket</label>
          <input type="number" name="quantity" min="1" max="20" value="1" class="form-control" required>
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label>Total Bayar (Rp)</label>
          <input type="number" name="total_amount" min="0" value="75000" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="status" class="form-control">
            <option value="pending">Pending</option>
            <option value="confirmed">Terkonfirmasi</option>
            <option value="cancelled">Dibatalkan</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label>Catatan</label>
        <input type="text" name="notes" class="form-control" placeholder="Opsional">
      </div>
      <div class="dialog-foot">
        <button type="button" class="btn btn-outline" data-close-dialog>Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Pesanan</button>
      </div>
    </form>
  </dialog>
@endsection
