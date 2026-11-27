@extends('admin.layouts.app')

@section('title', 'Pengaturan Event')
@section('page_title', 'Pengaturan Event GALAKSI')

@section('content')
  <div class="panel-card" style="max-width: 780px;">
    <div class="panel-header">
      <h2 class="panel-title">Konfigurasi Landing Page &amp; Tautan Pendaftaran</h2>
    </div>
    <form method="POST" action="{{ route('admin.settings.update') }}" class="panel-body">
      @csrf
      @method('PUT')

      <div class="form-grid">
        <div class="form-group">
          <label for="event_year">Tahun Acara</label>
          <input id="event_year" type="text" name="event_year" value="{{ old('event_year', $setting->event_year) }}" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="open_gate_label">Jam Open Gate</label>
          <input id="open_gate_label" type="text" name="open_gate_label" value="{{ old('open_gate_label', $setting->open_gate_label) }}" class="form-control" required>
        </div>
      </div>

      <div class="form-group">
        <label for="event_date_label">Label Tanggal Acara</label>
        <input id="event_date_label" type="text" name="event_date_label" value="{{ old('event_date_label', $setting->event_date_label) }}" class="form-control" required>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label for="venue_name">Nama Gedung / Venue</label>
          <input id="venue_name" type="text" name="venue_name" value="{{ old('venue_name', $setting->venue_name) }}" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="venue_city">Institusi / Lokasi</label>
          <input id="venue_city" type="text" name="venue_city" value="{{ old('venue_city', $setting->venue_city) }}" class="form-control" required>
        </div>
      </div>

      <div class="form-group">
        <label for="ticket_form_url">URL Formulir Tiket</label>
        <input id="ticket_form_url" type="text" name="ticket_form_url" value="{{ old('ticket_form_url', $setting->ticket_form_url) }}" class="form-control" placeholder="https://...">
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label for="tenant_url">URL Info Tenant</label>
          <input id="tenant_url" type="text" name="tenant_url" value="{{ old('tenant_url', $setting->tenant_url) }}" class="form-control" placeholder="https://...">
        </div>
        <div class="form-group">
          <label for="sponsor_url">URL Info Sponsor</label>
          <input id="sponsor_url" type="text" name="sponsor_url" value="{{ old('sponsor_url', $setting->sponsor_url) }}" class="form-control" placeholder="https://...">
        </div>
      </div>

      <div style="margin-top: 8px;">
        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
      </div>
    </form>
  </div>
@endsection
