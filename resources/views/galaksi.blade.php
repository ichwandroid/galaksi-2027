<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'GALAKSI 2027 — Sekolah Alam Anak Saleh' }}</title>
  <meta name="description" content="{{ $metaDescription ?? 'Gelaran Aktualisasi Talenta Seni dan Prestasi Sekolah Alam Anak Saleh — Sabtu, 16 Januari 2027 di Graha Cakrawala Universitas Negeri Malang.' }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&display=swap">
  <link rel="stylesheet" href="{{ asset('css/galaksi.css') }}">
</head>
<body>
@php
  $year = $year ?? '2027';
  $ticketHref = $ticketFormUrl ?? '#form-tiket';
  $tenantHref = $tenantUrl ?? '#info-tenant';
  $sponsorHref = $sponsorUrl ?? '#info-sponsor';
  $emblemSrc = $emblemSrc ?? asset('assets/aistudio/logo_GALAKSI_NEW_c6e4942a.png');
  $saansLogoSrc = $saansLogoSrc ?? asset('assets/aistudio/7b00066b-1521-4140-86aa-1cafbd042cf7_aff1575e.png');

  $socials = collect($socialLinks ?? [
      ['platform' => 'Instagram', 'handle' => '@sekolahalamanaksaleh', 'href' => null],
      ['platform' => 'YouTube', 'handle' => 'Anak Saleh', 'href' => null],
      ['platform' => 'TikTok', 'handle' => '@galaksi.saans', 'href' => null],
  ])->filter(fn ($item) => strtolower($item['platform'] ?? '') !== 'website');

  $taglineLines = [
      ['text' => 'Pesan', 'small' => false],
      ['text' => 'tiket', 'small' => true],
      ['text' => 'Galaksi-mu', 'small' => false],
      ['text' => 'sekarang', 'small' => true],
  ];

  $navItems = ['Cover', 'Galaksi', 'Jenjang', 'Expo', 'Jadwal', 'Tiketing', 'Kontak'];
@endphp

<div class="galaksi-shell">
  <div class="galaksi-bg-wrap">
    <canvas id="galaksi-bg-canvas" class="galaksi-bg-canvas" aria-hidden="true"></canvas>
  </div>

  <main class="galaksi-main">
    <section
      id="galaksi-specimen-root"
      class="galaksi-root"
      data-emblem-src="{{ $emblemSrc }}"
      aria-label="GALAKSI 2027 — Sekolah Alam Anak Saleh"
    >
      <div class="lys-stage">
        <div class="lys-vignette" aria-hidden="true"></div>
        <div class="lys-glow" aria-hidden="true"></div>
        <canvas class="lys-gl-canvas" aria-hidden="true"></canvas>

        {{-- ================================ 0 · cover ================================ --}}
        <div class="lys-scene-panel is-active" data-scene-panel="0">
          <div class="lys-cover-top">
            <div class="lys-display lys-hidden-init lys-cover-heading" data-sc="0" data-fx="clip" data-d="0">
              Sekolah Alam<br>Anak Saleh
            </div>
            <div class="lys-display lys-hidden-init lys-cover-tagline" data-sc="0" data-fx="clip" data-d="0.5">
              Gelaran Aktualisasi<br>Talenta Seni<br>dan Prestasi
            </div>
          </div>

          <div class="lys-cover-bottom">
            <div class="lys-hidden-init" data-sc="0" data-fx="rise" data-d="0.3">
              <img
                src="{{ $saansLogoSrc }}"
                alt="SAANS — Sekolah Alam Anak Saleh"
                class="lys-saans-cover-logo"
                draggable="false"
              >
            </div>
          </div>
        </div>

        {{-- ================================ 1 · galaksi ============================== --}}
        <div class="lys-scene-panel" data-scene-panel="1">
          <div class="lys-sec2">
            <div class="lys-eyebrow-row">
              <span class="lys-sans lys-hidden-init lys-sec2-eyebrow" data-sc="1" data-fx="rise" data-d="0">
                Babak I — Sekolah Alam Anak Saleh
              </span>
              <span class="lys-hidden-init lys-eyebrow-rule" data-sc="1" data-fx="line" data-d="0.06"></span>
            </div>
            <h2 class="lys-display lys-hidden-init" data-sc="1" data-fx="clip" data-d="0.1">
              Pertunjukan <em>Teater Musikal</em>
            </h2>
            <div class="lys-hidden-init lys-sec2-line" data-sc="1" data-fx="line" data-d="0.18"></div>
            <p class="lys-display lys-hidden-init lys-sec2-lead" data-sc="1" data-fx="rise" data-d="0.24">
              GALAKSI (Gelaran Aktualisasi Talenta Seni dan Prestasi) dirancang sebagai pertunjukan teater musikal yang memadukan akting, dialog, musik, lagu, gerak, dan tata artistik dalam satu cerita.
            </p>
            <p class="lys-display lys-hidden-init lys-sec2-sub" data-sc="1" data-fx="rise" data-d="0.36">
              Pertunjukan ini melibatkan siswa Playgroup, TK, SD, dan SMP untuk berkarya bersama, dengan peran dan kontribusi yang disesuaikan dengan tahap perkembangan serta kesiapan masing-masing.
            </p>
            <div class="lys-sans lys-hidden-init lys-sec2-keywords" data-sc="1" data-fx="rise" data-d="0.48">
              Akting · Dialog · Musik · Lagu · Gerak · Tata Artistik
            </div>
          </div>
          <div class="lys-sans lys-hidden-init lys-scene-caption" data-sc="1" data-fx="rise" data-d="0.6">
            <span class="lys-crimson">02</span> — Teater Musikal Galaksi
          </div>
        </div>

        {{-- =============================== 2 · jenjang ============================== --}}
        <div class="lys-scene-panel" data-scene-panel="2">
          <div class="lys-sec2">
            <div class="lys-eyebrow-row">
              <span class="lys-sans lys-hidden-init lys-sec2-eyebrow" data-sc="2" data-fx="rise" data-d="0">
                Babak II — Kolaborasi Lintas Jenjang
              </span>
              <span class="lys-hidden-init lys-eyebrow-rule" data-sc="2" data-fx="line" data-d="0.06"></span>
            </div>
            <h2 class="lys-display lys-hidden-init" data-sc="2" data-fx="clip" data-d="0.1">
              Satu Panggung <em>Semua Jenjang</em>
            </h2>
            <div class="lys-hidden-init lys-sec2-line" data-sc="2" data-fx="line" data-d="0.18"></div>
            <p class="lys-display lys-hidden-init lys-sec2-lead lys-sec2-lead--compact" data-sc="2" data-fx="rise" data-d="0.24">
              Setiap jenjang menjadi bagian dari satu rangkaian pertunjukan: siswa Playgroup dan TK berkontribusi melalui gerak dan ekspresi sederhana; siswa SD melalui tari, musik, dialog, dan karya kreatif; sementara siswa SMP dapat berperan dalam seni peran, musik, produksi, serta kolaborasi artistik.
            </p>
            <p class="lys-display lys-hidden-init lys-sec2-sub" data-sc="2" data-fx="rise" data-d="0.36">
              Seluruh bagian dirangkai menjadi pengalaman panggung yang utuh dan mencerminkan semangat belajar serta kreativitas siswa Sekolah Alam Anak Saleh.
            </p>
            <div class="lys-sans lys-hidden-init lys-sec2-keywords" data-sc="2" data-fx="rise" data-d="0.48">
              Playgroup · TK · SD · SMP
            </div>
          </div>
          <div class="lys-sans lys-hidden-init lys-scene-caption" data-sc="2" data-fx="rise" data-d="0.6">
            <span class="lys-crimson">03</span> — Rangkaian Antar Jenjang
          </div>
        </div>

        {{-- ================================= 3 · expo =============================== --}}
        <div class="lys-scene-panel" data-scene-panel="3">
          <div class="lys-sec2">
            <div class="lys-eyebrow-row">
              <span class="lys-sans lys-hidden-init lys-sec2-eyebrow" data-sc="3" data-fx="rise" data-d="0">
                Babak III — Pameran &amp; Ruang Publik
              </span>
              <span class="lys-hidden-init lys-eyebrow-rule" data-sc="3" data-fx="line" data-d="0.06"></span>
            </div>
            <h2 class="lys-display lys-hidden-init" data-sc="3" data-fx="clip" data-d="0.1">
              Expo Pendidikan <em>untuk Publik</em>
            </h2>
            <div class="lys-hidden-init lys-sec2-line" data-sc="3" data-fx="line" data-d="0.18"></div>
            <p class="lys-display lys-hidden-init lys-sec2-lead" data-sc="3" data-fx="rise" data-d="0.24">
              Selain pertunjukan, GALAKSI menghadirkan expo pendidikan yang terbuka bagi masyarakat. Expo menjadi ruang bagi pengunjung untuk mengenal karya, aktivitas, dan lingkungan belajar di Sekolah Alam Anak Saleh.
            </p>
            <p class="lys-display lys-hidden-init lys-sec2-sub" data-sc="3" data-fx="rise" data-d="0.36">
              GALAKSI menjadi wadah aktualisasi siswa sekaligus sarana bagi sekolah untuk berbagi pengalaman pendidikan dengan publik.
            </p>
            <div class="lys-sans lys-hidden-init lys-sec2-keywords" data-sc="3" data-fx="rise" data-d="0.48">
              Karya Siswa · Aktivitas · Lingkungan Belajar
            </div>
          </div>
          <div class="lys-sans lys-hidden-init lys-scene-caption" data-sc="3" data-fx="rise" data-d="0.6">
            <span class="lys-crimson">04</span> — Expo Pendidikan Galaksi
          </div>
        </div>

        {{-- ================================ 4 · jadwal ============================== --}}
        <div class="lys-scene-panel" data-scene-panel="4">
          <div class="lys-jadwal-wrap">
            <div class="lys-jadwal-top">
              <span class="lys-sans lys-hidden-init lys-jadwal-meta lys-jadwal-meta--muted" data-sc="4" data-fx="rise" data-d="0">
                Sekolah Alam Anak Saleh
              </span>
              <span class="lys-hidden-init lys-jadwal-rule" data-sc="4" data-fx="line" data-d="0.05"></span>
              <span class="lys-sans lys-hidden-init lys-jadwal-meta lys-jadwal-meta--crimson" data-sc="4" data-fx="rise" data-d="0">
                Galaksi {{ $year }}
              </span>
            </div>

            <div class="lys-display lys-hidden-init lys-jadwal-day" data-sc="4" data-fx="fade" data-d="0.08">
              Sabtu,
            </div>

            <h2 class="lys-display lys-hidden-init lys-jadwal-date" data-sc="4" data-fx="clip" data-d="0.14">
              16 Januari 2027
            </h2>

            <div class="lys-jadwal-gate-row">
              <span class="lys-hidden-init lys-jadwal-gate-line-left" data-sc="4" data-fx="line" data-d="0.22"></span>
              <span class="lys-sans lys-hidden-init lys-jadwal-gate" data-sc="4" data-fx="rise" data-d="0.24">
                (Open Gate 07.00 WIB)
              </span>
              <span class="lys-hidden-init lys-jadwal-gate-line-right" data-sc="4" data-fx="line" data-d="0.22"></span>
            </div>

            <div class="lys-display lys-hidden-init lys-jadwal-venue" data-sc="4" data-fx="clip" data-d="0.34">
              Graha Cakrawala
            </div>
            <div class="lys-display lys-hidden-init lys-jadwal-uni" data-sc="4" data-fx="rise" data-d="0.42">
              Universitas Negeri Malang
            </div>
          </div>
          <div class="lys-sans lys-hidden-init lys-scene-caption" data-sc="4" data-fx="rise" data-d="0.6">
            <span class="lys-crimson">05</span> — Jadwal &amp; Lokasi
          </div>
        </div>

        {{-- ================================ 5 · tiket =============================== --}}
        <div class="lys-scene-panel lys-display" data-scene-panel="5">
          <div class="lys-bloom">
            @foreach ($taglineLines as $i => $t)
              <div
                class="lys-hidden-init lys-bloom-line"
                data-sc="5"
                data-fx="clip"
                data-d="{{ $i * 0.14 }}"
                style="{{ $t['small']
                  ? 'font-size:0.4em;font-style:italic;line-height:1.15;margin-left:' . ($i < 2 ? '5.2em' : '4.4em') . ';margin-top:-0.08em;margin-bottom:0.25em;'
                  : 'margin-left:' . ($i > 1 ? '0.35em' : '0') . ';' }}"
              >
                {{ $t['text'] }}
              </div>
            @endforeach

            <div class="lys-hidden-init lys-bloom-cta" data-sc="5" data-fx="rise" data-d="0.65">
              <a
                href="{{ $ticketHref }}"
                class="lys-btn lys-sans"
                data-ticket-btn
              >
                <span>Buka Formulir Tiket</span>
                <span class="lys-btn-arr" aria-hidden="true">→</span>
              </a>
              <div class="lys-sans lys-hint-text" data-ticket-hint hidden>
                Tautan formulir pendaftaran tiket dapat dihubungkan melalui variabel <code>$ticketFormUrl</code> di controller/route Laravel.
              </div>
            </div>
          </div>
          <div class="lys-sans lys-hidden-init lys-scene-caption" data-sc="5" data-fx="rise" data-d="0.7">
            <span class="lys-crimson">06</span> — Tiketing Galaksi
          </div>
        </div>

        {{-- ============================== 6 · kontak =============================== --}}
        <div class="lys-scene-panel lys-display" data-scene-panel="6">
          <div class="lys-fin">
            {{-- Left: Sosial Media --}}
            <div class="lys-fin-left">
              <div class="lys-hidden-init lys-fin-title" data-sc="6" data-fx="clip" data-d="0">
                Galaksi
              </div>

              <div class="lys-hidden-init lys-social-row" data-sc="6" data-fx="rise" data-d="0.4">
                @foreach ($socials as $item)
                  @php
                    $platform = $item['platform'] ?? 'Social';
                    $key = strtolower($platform);
                    $handle = $item['handle'] ?? '';
                    $href = $item['href'] ?? null;
                    $isExternalSocial = $href && preg_match('/^https?:\/\//', $href);
                  @endphp

                  @if ($isExternalSocial)
                    <a
                      href="{{ $href }}"
                      target="_blank"
                      rel="noreferrer"
                      aria-label="{{ $platform }}"
                      title="{{ $platform }}"
                      class="lys-social-btn"
                    >
                      @if ($key === 'instagram')
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <rect x="2.5" y="2.5" width="19" height="19" rx="5.5" stroke="currentColor" stroke-width="1.65" />
                          <circle cx="12" cy="12" r="4.4" stroke="currentColor" stroke-width="1.65" />
                          <circle cx="17.4" cy="6.6" r="1.2" fill="currentColor" />
                        </svg>
                      @elseif ($key === 'youtube')
                        <svg width="26" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <rect x="2" y="4.8" width="20" height="14.4" rx="4.2" stroke="currentColor" stroke-width="1.65" />
                          <path d="M10.2 9.15L15.6 12L10.2 14.85V9.15Z" fill="currentColor" />
                        </svg>
                      @else
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <path d="M16.6 5.82A4.28 4.28 0 0 1 15.54 3h-3.09v12.4a2.59 2.59 0 0 1-2.59 2.5c-1.42 0-2.6-1.16-2.6-2.6 0-1.72 1.66-3.01 3.37-2.48V9.66c-3.45-.46-6.47 2.22-6.47 5.64 0 3.33 2.76 5.7 5.69 5.7 3.14 0 5.69-2.55 5.69-5.7V9.01a7.35 7.35 0 0 0 4.3 1.38V7.3c-1.15 0-2.42-.47-3.24-1.48z" fill="currentColor" />
                        </svg>
                      @endif
                    </a>
                  @else
                    <button
                      type="button"
                      aria-label="{{ $platform }}"
                      title="{{ $platform }}"
                      data-social-platform="{{ $platform }}"
                      data-social-handle="{{ $handle }}"
                      class="lys-social-btn"
                    >
                      @if ($key === 'instagram')
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <rect x="2.5" y="2.5" width="19" height="19" rx="5.5" stroke="currentColor" stroke-width="1.65" />
                          <circle cx="12" cy="12" r="4.4" stroke="currentColor" stroke-width="1.65" />
                          <circle cx="17.4" cy="6.6" r="1.2" fill="currentColor" />
                        </svg>
                      @elseif ($key === 'youtube')
                        <svg width="26" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <rect x="2" y="4.8" width="20" height="14.4" rx="4.2" stroke="currentColor" stroke-width="1.65" />
                          <path d="M10.2 9.15L15.6 12L10.2 14.85V9.15Z" fill="currentColor" />
                        </svg>
                      @else
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <path d="M16.6 5.82A4.28 4.28 0 0 1 15.54 3h-3.09v12.4a2.59 2.59 0 0 1-2.59 2.5c-1.42 0-2.6-1.16-2.6-2.6 0-1.72 1.66-3.01 3.37-2.48V9.66c-3.45-.46-6.47 2.22-6.47 5.64 0 3.33 2.76 5.7 5.69 5.7 3.14 0 5.69-2.55 5.69-5.7V9.01a7.35 7.35 0 0 0 4.3 1.38V7.3c-1.15 0-2.42-.47-3.24-1.48z" fill="currentColor" />
                        </svg>
                      @endif
                    </button>
                  @endif
                @endforeach
              </div>

              <div class="lys-hidden-init lys-fin-school" data-sc="6" data-fx="fade" data-d="0.6">
                Sekolah Alam Anak Saleh
              </div>
            </div>

            {{-- Right: Info Booth Tenant & Info Sponsor --}}
            <div class="lys-fin-right">
              <div class="lys-hidden-init lys-fin-eyebrow" data-sc="6" data-fx="fade" data-d="0.3">
                Info Booth &amp; Kemitraan
              </div>
              <div class="lys-hidden-init lys-fin-sub" data-sc="6" data-fx="clip" data-d="0.35">
                @php
                  $isExternalTenant = preg_match('/^https?:\/\//', $tenantHref);
                  $isExternalSponsor = preg_match('/^https?:\/\//', $sponsorHref);
                @endphp
                <a
                  href="{{ $tenantHref }}"
                  @if ($isExternalTenant) target="_blank" rel="noreferrer" @endif
                  data-partner-trigger="tenant"
                >Tenant</a><span class="lys-crimson">·</span><a
                  href="{{ $sponsorHref }}"
                  @if ($isExternalSponsor) target="_blank" rel="noreferrer" @endif
                  data-partner-trigger="sponsor"
                >Sponsor</a>
              </div>

              <div class="lys-hidden-init lys-fin-actions-wrap" data-sc="6" data-fx="fade" data-d="0.5">
                <div class="lys-fin-actions">
                  <a
                    href="{{ $tenantHref }}"
                    @if ($isExternalTenant) target="_blank" rel="noreferrer" @endif
                    data-partner-trigger="tenant"
                    class="lys-btn lys-sans"
                  >
                    <span>Info Tenant</span>
                    <span class="lys-btn-arr" aria-hidden="true">→</span>
                  </a>
                  <a
                    href="{{ $sponsorHref }}"
                    @if ($isExternalSponsor) target="_blank" rel="noreferrer" @endif
                    data-partner-trigger="sponsor"
                    class="lys-btn lys-sans"
                  >
                    <span>Info Sponsor</span>
                    <span class="lys-btn-arr" aria-hidden="true">→</span>
                  </a>
                </div>
                <div class="lys-sans lys-fin-hint" data-partner-hint hidden></div>
              </div>
            </div>
          </div>

          <div class="lys-sans lys-hidden-init lys-footer" data-sc="6" data-fx="rise" data-d="0.75">
            <span>SEKOLAH ALAM ANAK SALEH</span>
            <div class="lys-footer-logo">
              <img
                src="{{ $saansLogoSrc }}"
                alt="SAANS — Sekolah Alam Anak Saleh"
                class="lys-footer-logo-img"
                draggable="false"
              >
            </div>
            <span>GALAKSI {{ $year }} · DESIGN BY SAANS</span>
          </div>
        </div>

        {{-- ================================== nav ================================== --}}
        <nav aria-label="Navigasi bagian" class="lys-nav lys-sans">
          @foreach ($navItems as $i => $navLabel)
            <button type="button" data-jump="{{ $i }}" @if ($i === 0) aria-current="step" @endif>
              <span class="lys-lab">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }} {{ $navLabel }}</span>
              <span class="lys-tick"></span>
            </button>
          @endforeach
        </nav>

        {{-- Static print grain --}}
        <svg aria-hidden="true" class="lys-grain" width="100%" height="100%">
          <filter id="lys-grain">
            <feTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="2" stitchTiles="stitch" />
            <feColorMatrix values="0 0 0 0 1  0 0 0 0 1  0 0 0 0 1  0 0 0 0.55 0" />
          </filter>
          <rect width="100%" height="100%" filter="url(#lys-grain)" />
        </svg>
      </div>
    </section>
  </main>
</div>

<script src="{{ asset('js/galaksi-experience.js') }}" defer></script>
</body>
</html>
