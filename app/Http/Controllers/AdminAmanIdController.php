<?php

namespace App\Http\Controllers;

use App\Models\AmanId;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAmanIdController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'pending');
        $q = trim((string) $request->query('q', ''));

        $query = AmanId::query()->latest();

        if (in_array($status, ['pending', 'verified', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('first_name', 'like', "%{$q}%")
                  ->orWhere('last_name', 'like', "%{$q}%")
                  ->orWhere('aman_id', 'like', "%{$q}%")
                  ->orWhere('primary_phone', 'like', "%{$q}%")
                  ->orWhere('province', 'like', "%{$q}%");
            });
        }

        $counts = AmanId::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.aman-ids.index', [
            'items'  => $query->paginate(15)->withQueryString(),
            'status' => $status,
            'q'      => $q,
            'counts' => [
                'pending'  => $counts['pending'] ?? 0,
                'verified' => $counts['verified'] ?? 0,
                'rejected' => $counts['rejected'] ?? 0,
                'all'      => $counts->sum(),
            ],
        ]);
    }

    public function show(AmanId $amanId): View
    {
        return view('admin.aman-ids.show', [
            'item' => $amanId->load('reviewer'),
        ]);
    }

    // Printable ID card (only for verified Aman IDs)
    public function card(AmanId $amanId): View|RedirectResponse
    {
        if ($amanId->status !== 'verified') {
            return back()->with('success', 'Only verified Aman IDs can have a card printed. Approve it first.');
        }

        return view('admin.aman-ids.card', [
            'item'      => $amanId,
            'verifyUrl' => route('aman-id.show', $amanId), // what the QR code opens
        ]);
    }

    public function approve(AmanId $amanId): RedirectResponse
    {
        $amanId->update([
            'status'           => 'verified',
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', "Aman ID {$amanId->aman_id} approved.");
    }

    public function reject(Request $request, AmanId $amanId): RedirectResponse
    {
        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $amanId->update([
            'status'           => 'rejected',
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
            'rejection_reason' => $data['rejection_reason'],
        ]);

        return back()->with('success', "Aman ID {$amanId->aman_id} rejected.");
    }
}