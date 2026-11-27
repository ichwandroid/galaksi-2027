<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pemesanan Tiket — GALAKSI {{ $setting->event_year }}</title>
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

    <div class="ticket-grid">
      <div>
        <div class="ticket-eyebrow-row">
          <span class="ticket-eyebrow lys-sans">Tiketing Galaksi {{ $setting->event_year }}</span>
          <span class="ticket-eyebrow-rule"></span>
        </div>

        <h1 class="ticket-title">Pesan Tiket <em>Galaksi-mu</em></h1>
        <div class="ticket-divider"></div>

        <p class="ticket-lead">
          Saksikan pertunjukan teater musikal kolaborasi Playgroup, TK, SD, dan SMP Sekolah Alam Anak Saleh serta kunjungi Expo Pendidikan untuk publik.
        </p>

        <div class="ticket-event-box">
          <div class="ticket-event-row">
            <span class="ticket-event-label lys-sans">Tanggal</span>
            <span class="ticket-event-val lys-sans">{{ $setting->event_date_label }}</span>
          </div>
          <div class="ticket-event-row">
            <span class="ticket-event-label lys-sans">Open Gate</span>
            <span class="ticket-event-val lys-sans">{{ $setting->open_gate_label }}</span>
          </div>
          <div class="ticket-event-row">
            <span class="ticket-event-label lys-sans">Lokasi</span>
            <span class="ticket-event-val lys-sans">{{ $setting->venue_name }}, {{ $setting->venue_city }}</span>
          </div>
        </div>

        <div class="ticket-tiers" data-tier-cards>
          @foreach ($categories as $key => $cat)
            <div
              class="ticket-tier-card {{ old('category', 'Reguler') === $key ? 'is-selected' : '' }}"
              data-tier-option="{{ $key }}"
              data-tier-price="{{ $cat['price'] }}"
            >
              <div class="ticket-tier-head">
                <span class="ticket-tier-name">{{ $cat['label'] }}</span>
                <span class="ticket-tier-price lys-sans">Rp {{ number_format($cat['price'], 0, ',', '.') }}</span>
              </div>
              <div class="ticket-tier-desc lys-sans">{{ $cat['desc'] }}</div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="ticket-panel">
        <h2 class="ticket-panel-title">Formulir Pemesanan</h2>

        @if ($errors->any())
          <div class="ticket-error-box lys-sans">
            <strong>Mohon periksa kembali isian Anda:</strong>
            <ul style="margin: 6px 0 0 18px; padding: 0;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('tickets.store') }}">
          @csrf

          <div class="form-field">
            <label for="buyer_name" class="lys-sans">Nama Lengkap</label>
            <input id="buyer_name" type="text" name="buyer_name" value="{{ old('buyer_name') }}" class="form-input" placeholder="Masukkan nama lengkap pemesan" required>
          </div>

          <div class="form-row-2">
            <div class="form-field">
              <label for="buyer_email" class="lys-sans">Email</label>
              <input id="buyer_email" type="email" name="buyer_email" value="{{ old('buyer_email') }}" class="form-input" placeholder="nama@email.com" required>
            </div>

            <div class="form-field">
              <label for="buyer_phone" class="lys-sans">No. WhatsApp</label>
              <input id="buyer_phone" type="text" name="buyer_phone" value="{{ old('buyer_phone') }}" class="form-input" placeholder="08xxxxxxxxxx" required>
            </div>
          </div>

          <div class="form-row-2">
            <div class="form-field">
              <label for="category" class="lys-sans">Kategori Tiket</label>
              <select id="category" name="category" class="form-input" data-category-select>
                @foreach ($categories as $key => $cat)
                  <option value="{{ $key }}" data-price="{{ $cat['price'] }}" @selected(old('category', 'Reguler') === $key)>
                    {{ $cat['label'] }} — Rp {{ number_format($cat['price'], 0, ',', '.') }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-field">
              <label for="quantity" class="lys-sans">Jumlah Tiket</label>
              <input id="quantity" type="number" name="quantity" min="1" max="20" value="{{ old('quantity', 1) }}" class="form-input" data-quantity-input required>
            </div>
          </div>

          <div class="form-field">
            <label for="notes" class="lys-sans">Catatan / Nama Siswa &amp; Jenjang (Opsional)</label>
            <input id="notes" type="text" name="notes" value="{{ old('notes') }}" class="form-input" placeholder="Contoh: Wali murid ananda Zahra (SD 4)">
          </div>

          <div class="ticket-summary">
            <span class="ticket-summary-label lys-sans">Estimasi Total</span>
            <span class="ticket-summary-total" data-total-display>Rp 75.000</span>
          </div>

          <button type="submit" class="lys-btn lys-sans">
            <span>Pesan Tiket Sekarang</span>
            <span aria-hidden="true">→</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const select = document.querySelector('[data-category-select]');
    const qtyInput = document.querySelector('[data-quantity-input]');
    const totalDisplay = document.querySelector('[data-total-display]');
    const cards = document.querySelectorAll('[data-tier-option]');

    const updateTotal = () => {
      const opt = select.options[select.selectedIndex];
      const price = Number(opt?.dataset.price || 0);
      const qty = Math.max(1, Math.min(20, Number(qtyInput.value || 1)));
      const total = price * qty;
      totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');

      cards.forEach((card) => {
        card.classList.toggle('is-selected', card.dataset.tierOption === select.value);
      });
    };

    cards.forEach((card) => {
      card.addEventListener('click', () => {
        select.value = card.dataset.tierOption;
        updateTotal();
      });
    });

    select.addEventListener('change', updateTotal);
    qtyInput.addEventListener('input', updateTotal);
    updateTotal();
  });
</script>
</body>
</html>
