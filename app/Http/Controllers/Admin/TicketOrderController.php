<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TicketOrderController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $orders = TicketOrder::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($sub) use ($search): void {
                    $sub->where('order_code', 'like', "%{$search}%")
                        ->orWhere('buyer_name', 'like', "%{$search}%")
                        ->orWhere('buyer_email', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.tickets.index', [
            'orders' => $orders,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'buyer_name' => ['required', 'string', 'max:255'],
            'buyer_email' => ['required', 'email', 'max:255'],
            'buyer_phone' => ['required', 'string', 'max:32'],
            'category' => ['required', 'in:Reguler,VIP,Keluarga'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'total_amount' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:pending,confirmed,cancelled'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['order_code'] = 'GLK-2027-' . strtoupper(Str::random(5));

        TicketOrder::query()->create($validated);

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Pesanan tiket berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, TicketOrder $ticketOrder): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled'],
        ]);

        $ticketOrder->update($validated);

        return back()->with('success', "Status pesanan {$ticketOrder->order_code} diperbarui.");
    }

    public function destroy(TicketOrder $ticketOrder): RedirectResponse
    {
        $code = $ticketOrder->order_code;
        $ticketOrder->delete();

        return back()->with('success', "Pesanan {$code} dihapus.");
    }
}
