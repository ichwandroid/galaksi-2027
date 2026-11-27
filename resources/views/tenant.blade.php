<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Info Tenant UMKM — GALAKSI {{ $setting->event_year }}</title>
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
        <span class="ticket-header-meta lys-sans"><img style="width: auto;" src="{{ asset('assets/aistudio/7b00066b-1521-4140-86aa-1cafbd042cf7_aff1575e.png') }}" alt=""></span>
      </div>
    </header>

    <div class="ticket-grid">
      {{-- Kolom Kiri: Sapaan, Ketentuan, & Dukungan Kolaborasi --}}
      <div>
        <div class="ticket-eyebrow-row">
          <span class="ticket-eyebrow lys-sans">Info Booth &amp; Kemitraan UMKM</span>
          <span class="ticket-eyebrow-rule"></span>
        </div>

        <h1 class="ticket-title">Bergabung sebagai <em>Tenant UMKM</em></h1>
        <div class="ticket-divider"></div>

        <p class="ticket-lead">
          Halo Kak! Terima kasih sudah tertarik untuk bergabung sebagai tenant di acara kami. Berikut adalah informasi dan persyaratan untuk pendaftaran awal gerai:
        </p>

        <div class="ticket-event-box">
          <div class="ticket-event-label lys-sans" style="color: #e6e1cf; margin-bottom: 12px;">
            Ketentuan Pendaftaran &amp; Pembayaran
          </div>
          <ul class="umkm-list lys-sans">
            <li><strong>Pembayaran DP:</strong> Minimal 50% dari biaya sewa booth saat melakukan pendaftaran.</li>
            <li><strong>Batas Pelunasan:</strong> Maksimal H-7 sebelum acara berlangsung (paling lambat tanggal <strong>8 Januari 2027</strong>).</li>
            <li><strong>Kebijakan Pembatalan:</strong> Dana (DP maupun pelunasan) yang sudah masuk tidak dapat ditarik kembali (<em>non-refundable</em>).</li>
          </ul>
        </div>

        <div class="ticket-event-box" style="margin-bottom: 0;">
          <div class="ticket-event-label lys-sans" style="color: #e6e1cf; margin-bottom: 10px;">
            Dukungan Kolaborasi UMKM
          </div>
          <p class="umkm-list lys-sans" style="padding-left: 0; margin: 0;">
            Sebagai bentuk dukungan serta promosi gerai Kakak selama acara berlangsung, kami menyiapkan <strong>VOUCHER</strong> yang nantinya akan kami bagikan kepada seluruh guru, karyawan, serta tamu <strong>VIP &amp; VVIP</strong> untuk berbelanja di tenant UMKM.
          </p>
        </div>
      </div>

      {{-- Kolom Kanan: Langkah Pendaftaran, Rekening, & CP --}}
      <div class="ticket-panel">
        <h2 class="ticket-panel-title">Cara Mengamankan Slot Tenant</h2>

        <div class="umkm-steps">
          <div class="umkm-step">
            <div class="umkm-step-head">
              <span class="umkm-step-num lys-sans">01</span>
              <span class="umkm-step-title">Transfer DP / Pelunasan Tenant</span>
            </div>
            <p class="ticket-tier-desc lys-sans" style="margin: 0;">
              Rekening Pentas Seni Galaksi atas nama <strong style="color: #fff;">Yayasan Pendidikan Anak Saleh</strong>:
            </p>
            <div class="bank-grid">
              @foreach ([
                  ['bank' => 'BNI', 'number' => '388788781'],
                  ['bank' => 'BSI', 'number' => '2026202704'],
                  ['bank' => 'BRI', 'number' => '034401002761309'],
                  ['bank' => 'Bank Jatim', 'number' => '0041020202'],
              ] as $acc)
                <div class="bank-card">
                  <div>
                    <div class="bank-name lys-sans">{{ $acc['bank'] }}</div>
                    <div class="bank-num lys-sans">{{ $acc['number'] }}</div>
                  </div>
                  <button type="button" class="bank-copy-btn lys-sans" data-copy="{{ $acc['number'] }}">Salin</button>
                </div>
              @endforeach
            </div>
          </div>

          <div class="umkm-step">
            <div class="umkm-step-head">
              <span class="umkm-step-num lys-sans">02</span>
              <span class="umkm-step-title">Isi Formulir Pendaftaran (Google Form)</span>
            </div>
            <p class="ticket-tier-desc lys-sans" style="margin: 0 0 14px;">
              Lengkapi data usaha dan unggah bukti transfer DP/pelunasan melalui tautan resmi berikut:
            </p>
            <a href="https://forms.gle/FgsQTtQe7M48PUho9" target="_blank" rel="noreferrer" class="lys-btn lys-sans">
              <span>Buka Google Form Tenant</span>
              <span aria-hidden="true">→</span>
            </a>
          </div>

          <div class="umkm-step">
            <div class="umkm-step-head">
              <span class="umkm-step-num lys-sans">03</span>
              <span class="umkm-step-title">Join Grup WhatsApp Tenant</span>
            </div>
            <p class="ticket-tier-desc lys-sans" style="margin: 0;">
              Tautan grup WhatsApp resmi Tenant tersedia di halaman akhir setelah Kakak mengirimkan Google Form.
            </p>
          </div>
        </div>

        <div class="ticket-event-box" style="margin-top: 22px; margin-bottom: 0;">
          <div class="ticket-event-row">
            <span class="ticket-event-label lys-sans">CP Tim Tenant</span>
            <span class="ticket-event-val lys-sans">Ustadz Danny · 081357576773</span>
          </div>
          <div style="margin-top: 14px;">
            <a href="https://wa.me/6281357576773" target="_blank" rel="noreferrer" class="lys-btn lys-btn-ghost lys-sans">
              <span>Hubungi Ustadz Danny via WhatsApp</span>
              <span aria-hidden="true">→</span>
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-copy]').forEach((btn) => {
      btn.addEventListener('click', async () => {
        const value = btn.getAttribute('data-copy');
        try {
          await navigator.clipboard.writeText(value);
          const original = btn.textContent;
          btn.textContent = 'Tersalin';
          setTimeout(() => { btn.textContent = original; }, 1600);
        } catch (e) {
          // Clipboard fallback ignored
        }
      });
    });
  });
</script>
</body>
</html>
