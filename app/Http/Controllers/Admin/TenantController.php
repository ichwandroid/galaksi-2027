<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $tenants = Tenant::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($sub) use ($search): void {
                    $sub->where('business_name', 'like', "%{$search}%")
                        ->orWhere('owner_name', 'like', "%{$search}%")
                        ->orWhere('booth_code', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.tenants.index', [
            'tenants' => $tenants,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'category' => ['required', 'in:Kuliner,Kriya & Seni,Edukasi,Fashion,Lainnya'],
            'booth_code' => ['nullable', 'string', 'max:32'],
            'status' => ['required', 'in:pending,approved,rejected'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        Tenant::query()->create($validated);

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Data tenant berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, Tenant $tenant): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'booth_code' => ['nullable', 'string', 'max:32'],
        ]);

        $tenant->update($validated);

        return back()->with('success', "Status tenant {$tenant->business_name} diperbarui.");
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        $name = $tenant->business_name;
        $tenant->delete();

        return back()->with('success', "Tenant {$name} dihapus.");
    }
}
