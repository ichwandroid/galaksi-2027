<?php

namespace Tests\Feature;

use App\Models\TicketOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_form_page_renders(): void
    {
        $this->get('/tiket')
            ->assertOk()
            ->assertSee('Pesan Tiket')
            ->assertSee('Formulir Pemesanan');
    }

    public function test_visitor_can_submit_ticket_order_and_view_receipt(): void
    {
        $response = $this->post('/tiket', [
            'buyer_name' => 'Dewi Anggraini',
            'buyer_email' => 'dewi@example.com',
            'buyer_phone' => '081234567899',
            'category' => 'VIP',
            'quantity' => 3,
            'notes' => 'Wali murid SD 2',
        ]);

        $order = TicketOrder::query()->firstOrFail();

        $this->assertSame('Dewi Anggraini', $order->buyer_name);
        $this->assertSame(450000, $order->total_amount);
        $this->assertSame('pending', $order->status);

        $response->assertRedirect("/tiket/{$order->order_code}");

        $this->get("/tiket/{$order->order_code}")
            ->assertOk()
            ->assertSee($order->order_code)
            ->assertSee('Dewi Anggraini')
            ->assertSee('450.000');
    }
}
