<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventSettingController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\TicketOrderController;
use App\Http\Controllers\TicketRegistrationController;
use App\Models\EventSetting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    $setting = Schema::hasTable('event_settings')
        ? EventSetting::current()
        : null;

    $resolveUrl = static function (?string $configured, string $fallback): string {
        if (! $configured) {
            return $fallback;
        }

        $host = parse_url($configured, PHP_URL_HOST);
        if (in_array($host, ['localhost', '127.0.0.1'], true)) {
            return $fallback;
        }

        return $configured;
    };

    return view('galaksi', [
        'year' => $setting?->event_year ?? '2027',
        'ticketFormUrl' => $resolveUrl($setting?->ticket_form_url, '/tiket'),
        'tenantUrl' => $resolveUrl($setting?->tenant_url, '/tenant'),
        'sponsorUrl' => $resolveUrl($setting?->sponsor_url, '/sponsor'),
        'socialLinks' => [
            ['platform' => 'Instagram', 'handle' => '@sekolahalamanaksaleh', 'href' => 'https://instagram.com/sekolahalamanaksaleh'],
            ['platform' => 'YouTube', 'handle' => 'Anak Saleh', 'href' => 'https://youtube.com/@sekolahalamanaksaleh'],
            ['platform' => 'TikTok', 'handle' => '@galaksi.saans', 'href' => 'https://tiktok.com/@galaksi.saans'],
        ],
    ]);
})->name('home');

Route::get('/tiket', [TicketRegistrationController::class, 'create'])->name('tickets.create');
Route::post('/tiket', [TicketRegistrationController::class, 'store'])->name('tickets.store');
Route::get('/tiket/{orderCode}', [TicketRegistrationController::class, 'show'])->name('tickets.show');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/tiket', [TicketOrderController::class, 'index'])->name('tickets.index');
        Route::post('/tiket', [TicketOrderController::class, 'store'])->name('tickets.store');
        Route::patch('/tiket/{ticketOrder}/status', [TicketOrderController::class, 'updateStatus'])->name('tickets.status');
        Route::delete('/tiket/{ticketOrder}', [TicketOrderController::class, 'destroy'])->name('tickets.destroy');

        Route::get('/tenant', [TenantController::class, 'index'])->name('tenants.index');
        Route::post('/tenant', [TenantController::class, 'store'])->name('tenants.store');
        Route::patch('/tenant/{tenant}/status', [TenantController::class, 'updateStatus'])->name('tenants.status');
        Route::delete('/tenant/{tenant}', [TenantController::class, 'destroy'])->name('tenants.destroy');

        Route::get('/sponsor', [SponsorController::class, 'index'])->name('sponsors.index');
        Route::post('/sponsor', [SponsorController::class, 'store'])->name('sponsors.store');
        Route::patch('/sponsor/{sponsor}/status', [SponsorController::class, 'updateStatus'])->name('sponsors.status');
        Route::delete('/sponsor/{sponsor}', [SponsorController::class, 'destroy'])->name('sponsors.destroy');

        Route::get('/pengaturan', [EventSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/pengaturan', [EventSettingController::class, 'update'])->name('settings.update');
    });
});
