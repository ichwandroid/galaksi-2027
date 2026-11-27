<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin — GALAKSI 2027</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/aistudio/logo_GALAKSI_NEW_c6e4942a.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="auth-shell">
  <div class="auth-card">
    <div class="auth-brand">
      <span class="auth-brand-badge">Sekolah Alam Anak Saleh</span>
      <h1>GALAKSI 2027</h1>
      <p>Masuk ke panel administrator acara</p>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
      @csrf
      <div class="form-group">
        <label for="email">Email Administrator</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus placeholder="admin@galaksi.sch.id">
      </div>

      <div class="form-group">
        <label for="password">Kata Sandi</label>
        <input id="password" type="password" name="password" class="form-control" required placeholder="••••••••">
      </div>

      <div style="display: flex; align-items: center; justify-content: space-between; margin: 14px 0 20px; font-size: 13px;">
        <label style="display: flex; align-items: center; gap: 7px; cursor: pointer;">
          <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
          <span>Ingat saya</span>
        </label>
        <a href="{{ route('home') }}" style="color: var(--text-muted);">Kembali ke beranda</a>
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; padding: 11px;">
        Masuk ke Dashboard
      </button>
    </form>
  </div>
</div>
</body>
</html>
