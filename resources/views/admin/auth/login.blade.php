<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin — GALAKSI 2027</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/aistudio/logo_GALAKSI_NEW_c6e4942a.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&display=swap">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="auth-shell">
  <div class="galaksi-vignette" aria-hidden="true"></div>
  <svg aria-hidden="true" class="galaksi-grain" width="100%" height="100%">
    <filter id="lys-grain">
      <feTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="2" stitchTiles="stitch" />
      <feColorMatrix values="0 0 0 0 1  0 0 0 0 1  0 0 0 0 1  0 0 0 0.55 0" />
    </filter>
    <rect width="100%" height="100%" filter="url(#lys-grain)" />
  </svg>

  <div class="auth-card">
    <div class="auth-brand">
      <img src="{{ asset('assets/aistudio/7b00066b-1521-4140-86aa-1cafbd042cf7_aff1575e.png') }}" alt="GALAKSI 2027" class="auth-logo">
      <h1>GALAKSI 2027</h1>
      <p>Masuk ke panel administrator acara</p>
    </div>

    <div class="auth-divider" aria-hidden="true"></div>

    @if ($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
      @csrf
      <div class="form-group">
        <label for="email">Email Administrator</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="email" placeholder="admin@galaksi.sch.id">
      </div>

      <div class="form-group">
        <label for="password">Kata Sandi</label>
        <div class="password-wrap">
          <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password" placeholder="••••••••">
          <button type="button" class="password-toggle" data-toggle-password aria-label="Tampilkan kata sandi">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="auth-footer-row">
        <label class="auth-remember">
          <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
          <span>Ingat saya</span>
        </label>
        <a href="{{ route('home') }}" class="auth-back-link">← Beranda</a>
      </div>

      <button type="submit" class="btn btn-primary btn-full">
        <span>Masuk ke Dashboard</span>
        <span aria-hidden="true">→</span>
      </button>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.querySelector('[data-toggle-password]');
    const pwdInput = document.getElementById('password');
    if (toggleBtn && pwdInput) {
      toggleBtn.addEventListener('click', () => {
        const show = pwdInput.type === 'password';
        pwdInput.type = show ? 'text' : 'password';
        toggleBtn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
      });
    }
  });
</script>
</body>
</html>
