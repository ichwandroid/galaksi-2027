<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bukti Pemesanan {{ $order->order_code }} — GALAKSI {{ $setting->event_year }}</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/aistudio/logo_GALAKSI_NEW_c6e4942a.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('assets/aistudio/logo_GALAKSI_NEW_c6e4942a.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&display=swap">
  <link rel="stylesheet" href="{{ asset('css/galaksi-ticket.css') }}">
</head>
<body>
<div class="ticket-shell">
  <div class="ticket-vignette" aria-hidden="true"></div>
  <svg aria-hidden="true" class="ticket-grain" width="100%" height="100%">
    <filter id="lys-grain">
      <feTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="2" stitchTiles="stitch" />
      <feColorMatrix values="0 0 0 0 1  0 0 0 0 1  0 0 0 0 1  0 0 0 0.55 0" />
    </filter>
    <rect width="100%" height="100%" filter="url(#lys-grain)" />
  </svg>

  <div class="ticket-container">
    <header class="ticket-header">
      <a href="{{ route('home') }}" class="ticket-back-link lys-sans">
        <span aria-hidden="true">←</span>
        <span>Kembali ke Galaksi</span>
      </a>

      <div class="ticket-header-brand">
        <span class="ticket-header-meta lys-sans">Sekolah Alam Anak Saleh · {{ $setting->event_year }}</span>
      </div>
    </header>

    <div class="receipt-card">
      <div class="ticket-eyebrow-row">
        <span class="ticket-eyebrow lys-sans">Pemesanan Tercatat</span>
        <span class="ticket-eyebrow-rule"></span>
      </div>

      <h1 class="ticket-title" style="font-size: clamp(28px, 3.6vw, 40px);">
        Terima kasih, <em>{{ $order->buyer_name }}</em>
      </h1>

      <div class="receipt-code-box">
        <div>
          <div class="ticket-event-label lys-sans" style="margin-bottom: 4px;">Kode Pemesanan</div>
          <div class="receipt-code lys-sans">{{ $order->order_code }}</div>
        </div>
        <span class="receipt-status lys-sans">
          {{ $order->status === 'confirmed' ? 'Terkonfirmasi' : ($order->status === 'cancelled' ? 'Dibatalkan' : 'Menunggu Konfirmasi') }}
        </span>
      </div>

      <div class="ticket-event-box" style="margin-bottom: 0;">
        <div class="ticket-event-row">
          <span class="ticket-event-label lys-sans">Acara</span>
          <span class="ticket-event-val lys-sans">GALAKSI {{ $setting->event_year }} · {{ $setting->event_date_label }}</span>
        </div>
        <div class="ticket-event-row">
          <span class="ticket-event-label lys-sans">Lokasi</span>
          <span class="ticket-event-val lys-sans">{{ $setting->venue_name }}, {{ $setting->venue_city }}</span>
        </div>
        <div class="ticket-event-row">
          <span class="ticket-event-label lys-sans">Kategori &amp; Jumlah</span>
          <span class="ticket-event-val lys-sans">{{ $order->category }} · {{ $order->quantity }} tiket</span>
        </div>
        <div class="ticket-event-row">
          <span class="ticket-event-label lys-sans">Total Pembayaran</span>
          <span class="ticket-event-val lys-sans" style="font-weight: 700;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
        <div class="ticket-event-row">
          <span class="ticket-event-label lys-sans">Kontak</span>
          <span class="ticket-event-val lys-sans">{{ $order->buyer_email }} · {{ $order->buyer_phone }}</span>
        </div>
      </div>

      <p class="ticket-lead lys-sans" style="font-size: 13px; margin: 18px 0 0;">
        Simpan kode pemesanan Anda. Tim panitia akan melakukan konfirmasi dan verifikasi melalui WhatsApp atau email terdaftar.
      </p>

      <div class="receipt-actions">
        <a href="{{ route('tickets.create') }}" class="lys-btn lys-btn-ghost lys-sans">Pesan Tiket Lagi</a>
        <a href="{{ route('home') }}" class="lys-btn lys-sans">Kembali ke Beranda</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
