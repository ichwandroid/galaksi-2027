@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Ringkasan Dashboard')

@section('content')
  <div class="kpi-grid">
    <div class="kpi-card">
      <div class="kpi-label">Total Tiket Aktif</div>
      <div class="kpi-value">{{ number_format($ticketStats['total_tickets']) }}</div>
      <div class="kpi-meta">{{ $ticketStats['confirmed_tickets'] }} terkonfirmasi · {{ $ticketStats['pending_orders'] }} pesanan pending</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-label">Pendapatan Tiket</div>
      <div class="kpi-value">Rp {{ number_format($ticketStats['revenue'], 0, ',', '.') }}</div>
      <div class="kpi-meta">Dari {{ $ticketStats['total_orders'] }} total pesanan</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-label">Tenant Booth</div>
      <div class="kpi-value">{{ $tenantStats['total'] }}</div>
      <div class="kpi-meta">{{ $tenantStats['approved'] }} disetujui · {{ $tenantStats['pending'] }} menunggu</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-label">Dukungan Sponsor</div>
      <div class="kpi-value">Rp {{ number_format($sponsorStats['total_contribution'], 0, ',', '.') }}</div>
      <div class="kpi-meta">{{ $sponsorStats['confirmed'] }} sponsor deal · {{ $sponsorStats['prospect'] }} prospek</div>
    </div>
  </div>

  <div class="dashboard-grid">
    <div class="panel-card">
      <div class="panel-header">
        <h2 class="panel-title">Pesanan Tiket Terbaru</h2>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline btn-sm">Kelola Semua</a>
      </div>
      <div class="table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Pembeli</th>
              <th>Kategori</th>
              <th>Jumlah</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($recentTickets as $ticket)
              <tr>
                <td><strong>{{ $ticket->order_code }}</strong></td>
                <td>
                  <div>{{ $ticket->buyer_name }}</div>
                  <div style="font-size: 12px; color: var(--text-muted);">{{ $ticket->buyer_phone }}</div>
                </td>
                <td>{{ $ticket->category }}</td>
                <td>{{ $ticket->quantity }} tiket</td>
                <td>
                  @if ($ticket->status === 'confirmed')
                    <span class="badge badge-success">Terkonfirmasi</span>
                  @elseif ($ticket->status === 'pending')
                    <span class="badge badge-warning">Pending</span>
                  @else
                    <span class="badge badge-danger">Dibatalkan</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada pesanan tiket.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 20px;">
      <div class="panel-card">
        <div class="panel-header">
          <h2 class="panel-title">Distribusi Kategori Tiket</h2>
        </div>
        <div class="panel-body">
          @php $maxCat = max(1, (int) collect($ticketByCategory)->max()); @endphp
          @foreach (['VIP', 'Keluarga', 'Reguler'] as $cat)
            @php $qty = (int) ($ticketByCategory[$cat] ?? 0); @endphp
            <div class="bar-row">
              <div class="bar-label-row">
                <span>{{ $cat }}</span>
                <span>{{ $qty }} tiket</span>
              </div>
              <div class="bar-track">
                <div class="bar-fill" style="width: {{ round(($qty / $maxCat) * 100) }}%;"></div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="panel-card">
        <div class="panel-header">
          <h2 class="panel-title">Info Acara Aktif</h2>
          <a href="{{ route('admin.settings.edit') }}" class="btn btn-outline btn-sm">Ubah</a>
        </div>
        <div class="panel-body" style="font-size: 14px; display: flex; flex-direction: column; gap: 8px;">
          <div><strong>Acara:</strong> GALAKSI {{ $settings->event_year }}</div>
          <div><strong>Tanggal:</strong> {{ $settings->event_date_label }}</div>
          <div><strong>Open Gate:</strong> {{ $settings->open_gate_label }}</div>
          <div><strong>Lokasi:</strong> {{ $settings->venue_name }}, {{ $settings->venue_city }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="dashboard-grid" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
    <div class="panel-card">
      <div class="panel-header">
        <h2 class="panel-title">Tenant Terbaru</h2>
        <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
      </div>
      <div class="table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Usaha</th>
              <th>Kategori</th>
              <th>Booth</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($recentTenants as $tenant)
              <tr>
                <td><strong>{{ $tenant->business_name }}</strong></td>
                <td>{{ $tenant->category }}</td>
                <td>{{ $tenant->booth_code ?? '—' }}</td>
                <td>
                  @if ($tenant->status === 'approved')
                    <span class="badge badge-success">Disetujui</span>
                  @elseif ($tenant->status === 'pending')
                    <span class="badge badge-warning">Pending</span>
                  @else
                    <span class="badge badge-danger">Ditolak</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 20px;">Belum ada tenant.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="panel-card">
      <div class="panel-header">
        <h2 class="panel-title">Sponsor Terbaru</h2>
        <a href="{{ route('admin.sponsors.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
      </div>
      <div class="table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Instansi</th>
              <th>Tier</th>
              <th>Nilai</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($recentSponsors as $sponsor)
              <tr>
                <td><strong>{{ $sponsor->company_name }}</strong></td>
                <td><span class="badge badge-info">{{ $sponsor->tier }}</span></td>
                <td>Rp {{ number_format($sponsor->contribution_amount, 0, ',', '.') }}</td>
                <td>
                  @if ($sponsor->status === 'confirmed')
                    <span class="badge badge-success">Confirmed</span>
                  @elseif ($sponsor->status === 'prospect')
                    <span class="badge badge-warning">Prospek</span>
                  @else
                    <span class="badge badge-neutral">Declined</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 20px;">Belum ada sponsor.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
