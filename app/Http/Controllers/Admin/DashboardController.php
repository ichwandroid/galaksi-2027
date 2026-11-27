<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventSetting;
use App\Models\Sponsor;
use App\Models\Tenant;
use App\Models\TicketOrder;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $ticketStats = [
            'total_orders' => TicketOrder::query()->count(),
            'total_tickets' => (int) TicketOrder::query()->where('status', '!=', 'cancelled')->sum('quantity'),
            'confirmed_tickets' => (int) TicketOrder::query()->where('status', 'confirmed')->sum('quantity'),
            'pending_orders' => TicketOrder::query()->where('status', 'pending')->count(),
            'revenue' => (int) TicketOrder::query()->where('status', 'confirmed')->sum('total_amount'),
        ];

        $tenantStats = [
            'total' => Tenant::query()->count(),
            'approved' => Tenant::query()->where('status', 'approved')->count(),
            'pending' => Tenant::query()->where('status', 'pending')->count(),
        ];

        $sponsorStats = [
            'total' => Sponsor::query()->count(),
            'confirmed' => Sponsor::query()->where('status', 'confirmed')->count(),
            'prospect' => Sponsor::query()->where('status', 'prospect')->count(),
            'total_contribution' => (int) Sponsor::query()->where('status', 'confirmed')->sum('contribution_amount'),
        ];

        $ticketByCategory = TicketOrder::query()
            ->selectRaw('category, SUM(quantity) as total_qty')
            ->where('status', '!=', 'cancelled')
            ->groupBy('category')
            ->pluck('total_qty', 'category')
            ->all();

        $recentTickets = TicketOrder::query()->latest()->take(5)->get();
        $recentTenants = Tenant::query()->latest()->take(4)->get();
        $recentSponsors = Sponsor::query()->latest()->take(4)->get();
        $settings = EventSetting::current();

        return view('admin.dashboard', [
            'ticketStats' => $ticketStats,
            'tenantStats' => $tenantStats,
            'sponsorStats' => $sponsorStats,
            'ticketByCategory' => $ticketByCategory,
            'recentTickets' => $recentTickets,
            'recentTenants' => $recentTenants,
            'recentSponsors' => $recentSponsors,
            'settings' => $settings,
        ]);
    }
}
