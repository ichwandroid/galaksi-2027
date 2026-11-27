<?php

namespace App\Http\Controllers;

use App\Models\EventSetting;
use App\Models\TicketOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TicketRegistrationController extends Controller
{
    /**
     * Ticket categories and unit prices (IDR).
     *
     * @return array<string, array{label: string, price: int, desc: string}>
     */
    public static function categories(): array
    {
        return [
            'Reguler' => [
                'label' => 'Reguler',
                'price' => 75000,
                'desc' => 'Akses area pertunjukan utama & expo pendidikan.',
            ],
            'Keluarga' => [
                'label' => 'Keluarga',
                'price' => 100000,
                'desc' => 'Kursi zona keluarga dengan akses prioritas gerbang.',
            ],
            'VIP' => [
                'label' => 'VIP',
                'price' => 150000,
                'desc' => 'Baris depan panggung utama, goodie bag & konsumsi.',
            ],
        ];
    }

    public function create(): View
    {
        return view('tickets.create', [
            'setting' => EventSetting::current(),
            'categories' => self::categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $categories = self::categories();

        $validated = $request->validate([
            'buyer_name' => ['required', 'string', 'max:255'],
            'buyer_email' => ['required', 'email', 'max:255'],
            'buyer_phone' => ['required', 'string', 'max:32'],
            'category' => ['required', 'in:'.implode(',', array_keys($categories))],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $unitPrice = $categories[$validated['category']]['price'];
        $totalAmount = $unitPrice * (int) $validated['quantity'];

        do {
            $orderCode = 'GLK-2027-'.strtoupper(Str::random(5));
        } while (TicketOrder::query()->where('order_code', $orderCode)->exists());

        $order = TicketOrder::query()->create([
            'order_code' => $orderCode,
            'buyer_name' => $validated['buyer_name'],
            'buyer_email' => $validated['buyer_email'],
            'buyer_phone' => $validated['buyer_phone'],
            'category' => $validated['category'],
            'quantity' => (int) $validated['quantity'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('tickets.show', $order->order_code);
    }

    public function show(string $orderCode): View
    {
        $order = TicketOrder::query()
            ->where('order_code', $orderCode)
            ->firstOrFail();

        return view('tickets.show', [
            'order' => $order,
            'setting' => EventSetting::current(),
        ]);
    }
}
