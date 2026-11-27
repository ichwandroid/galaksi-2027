<?php

namespace Tests\Feature;

use App\Models\Sponsor;
use App\Models\Tenant;
use App\Models\TicketOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_non_admin_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_can_login_and_view_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@galaksi.sch.id',
            'password' => 'secret123',
            'is_admin' => true,
        ]);

        $this->post('/admin/login', [
            'email' => 'admin@galaksi.sch.id',
            'password' => 'secret123',
        ])->assertRedirect('/admin');

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Ringkasan Dashboard');
    }

    public function test_admin_can_manage_tickets_tenants_sponsors_and_settings(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        // Ticket creation & status update
        $this->post('/admin/tiket', [
            'buyer_name' => 'Ahmad Fauzi',
            'buyer_email' => 'ahmad@example.com',
            'buyer_phone' => '08123456000',
            'category' => 'VIP',
            'quantity' => 2,
            'total_amount' => 300000,
            'status' => 'pending',
        ])->assertRedirect('/admin/tiket');

        $ticket = TicketOrder::query()->firstOrFail();
        $this->patch("/admin/tiket/{$ticket->id}/status", ['status' => 'confirmed'])
            ->assertRedirect();
        $this->assertSame('confirmed', $ticket->fresh()->status);

        // Tenant creation & status update
        $this->post('/admin/tenant', [
            'business_name' => 'Warung Sehat',
            'owner_name' => 'Sari',
            'phone' => '081999888777',
            'category' => 'Kuliner',
            'booth_code' => 'C-01',
            'status' => 'pending',
        ])->assertRedirect('/admin/tenant');

        $tenant = Tenant::query()->firstOrFail();
        $this->patch("/admin/tenant/{$tenant->id}/status", [
            'status' => 'approved',
            'booth_code' => 'C-02',
        ])->assertRedirect();
        $this->assertSame('approved', $tenant->fresh()->status);
        $this->assertSame('C-02', $tenant->fresh()->booth_code);

        // Sponsor creation & status update
        $this->post('/admin/sponsor', [
            'company_name' => 'PT Maju Bersama',
            'contact_person' => 'Rudi',
            'phone' => '081222333444',
            'tier' => 'Gold',
            'contribution_amount' => 15000000,
            'status' => 'prospect',
        ])->assertRedirect('/admin/sponsor');

        $sponsor = Sponsor::query()->firstOrFail();
        $this->patch("/admin/sponsor/{$sponsor->id}/status", ['status' => 'confirmed'])
            ->assertRedirect();
        $this->assertSame('confirmed', $sponsor->fresh()->status);

        // Event settings update
        $this->put('/admin/pengaturan', [
            'event_year' => '2027',
            'event_date_label' => 'Sabtu, 16 Januari 2027',
            'open_gate_label' => '07.00 WIB',
            'venue_name' => 'Graha Cakrawala',
            'venue_city' => 'Universitas Negeri Malang',
            'ticket_form_url' => 'https://example.com/tiket',
            'tenant_url' => 'https://example.com/tenant',
            'sponsor_url' => 'https://example.com/sponsor',
        ])->assertRedirect();

        $this->get('/')->assertOk();
    }

    public function test_admin_can_logout(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post('/admin/logout')
            ->assertRedirect('/admin/login');

        $this->assertGuest();
    }
}
