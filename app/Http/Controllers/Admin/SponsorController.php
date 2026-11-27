<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SponsorController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $tier = (string) $request->query('tier', '');

        $sponsors = Sponsor::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($sub) use ($search): void {
                    $sub->where('company_name', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%");
                });
            })
            ->when($tier !== '', fn ($query) => $query->where('tier', $tier))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.sponsors.index', [
            'sponsors' => $sponsors,
            'search' => $search,
            'tier' => $tier,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'tier' => ['required', 'in:Platinum,Gold,Silver,Partner'],
            'contribution_amount' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:prospect,confirmed,declined'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        Sponsor::query()->create($validated);

        return redirect()->route('admin.sponsors.index')
            ->with('success', 'Data sponsor berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, Sponsor $sponsor): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:prospect,confirmed,declined'],
        ]);

        $sponsor->update($validated);

        return back()->with('success', "Status sponsor {$sponsor->company_name} diperbarui.");
    }

    public function destroy(Sponsor $sponsor): RedirectResponse
    {
        $name = $sponsor->company_name;
        $sponsor->delete();

        return back()->with('success', "Sponsor {$name} dihapus.");
    }
}
