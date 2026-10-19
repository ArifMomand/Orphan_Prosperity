<?php

namespace App\Http\Controllers;

use App\Models\AmanDonation;
use App\Models\AmanId;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminAmanDonationController extends Controller
{
    // Shared filters for the list and the CSV export
    private function filtered(Request $request)
    {
        $q      = trim((string) $request->query('q', ''));
        $method = $request->query('method');
        $from   = $request->query('from');
        $to     = $request->query('to');

        return AmanDonation::query()
            ->with('beneficiary')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('donor_name', 'like', "%{$q}%")
                      ->orWhere('donor_email', 'like', "%{$q}%")
                      ->orWhere('reference', 'like', "%{$q}%")
                      ->orWhereHas('beneficiary', fn ($b) => $b->where('aman_id', 'like', "%{$q}%"));
                });
            })
            ->when($method && isset(AmanDonation::METHODS[$method]), fn ($query) => $query->where('method', $method))
            ->when($from, fn ($query) => $query->whereDate('donated_at', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('donated_at', '<=', $to));
    }

    public function index(Request $request): View
    {
        $base = $this->filtered($request);

        return view('admin.aman-donations.index', [
            'items'  => (clone $base)->latest('donated_at')->latest('id')->paginate(20)->withQueryString(),
            'total'  => (clone $base)->sum('amount'),
            'count'  => (clone $base)->count(),
            'q'      => $request->query('q', ''),
            'method' => $request->query('method', ''),
            'from'   => $request->query('from', ''),
            'to'     => $request->query('to', ''),
        ]);
    }

    // Record a donation against one Aman ID
    public function store(Request $request, AmanId $amanId): RedirectResponse
    {
        if ($amanId->status !== 'verified') {
            return back()->with('success', 'Donations can only be recorded for verified Aman IDs.');
        }

        $data = $request->validate([
            'amount'      => ['required', 'numeric', 'min:0.01', 'max:1000000'],
            'donor_name'  => ['nullable', 'string', 'max:120'],
            'donor_email' => ['nullable', 'email', 'max:150'],
            'need'        => ['required', Rule::in(array_merge(['general', 'cash'], array_keys(AmanId::NEEDS)))],
            'method'      => ['required', Rule::in(array_keys(AmanDonation::METHODS))],
            'reference'   => ['nullable', 'string', 'max:120'],
            'note'        => ['nullable', 'string', 'max:500'],
            'donated_at'  => ['required', 'date', 'before_or_equal:today'],
        ]);

        $amanId->donations()->create($data + ['recorded_by' => Auth::id()]);

        return back()->with('success', 'Donation of $' . number_format($data['amount'], 2) . ' recorded for ' . $amanId->aman_id . '.');
    }

    public function destroy(AmanDonation $donation): RedirectResponse
    {
        $donation->delete();

        return back()->with('success', 'Donation removed.');
    }

    // Totals per Aman ID, per need, per month
    public function report(Request $request): View
    {
        $from = $request->query('from');
        $to   = $request->query('to');

        $base = fn () => AmanDonation::query()
            ->when($from, fn ($q) => $q->whereDate('donated_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('donated_at', '<=', $to));

        return view('admin.aman-donations.report', [
            'from'        => $from,
            'to'          => $to,
            'grandTotal'  => $base()->sum('amount'),
            'grandCount'  => $base()->count(),
            'fundedCount' => $base()->distinct('beneficiary_id')->count('beneficiary_id'),
            'verifiedCount' => AmanId::where('status', 'verified')->count(),

            'byAman' => $base()
                ->selectRaw('beneficiary_id, SUM(amount) as total, COUNT(*) as donations')
                ->groupBy('beneficiary_id')
                ->orderByDesc('total')
                ->with('beneficiary')
                ->limit(50)
                ->get(),

            'byNeed' => $base()
                ->selectRaw('need, SUM(amount) as total, COUNT(*) as donations')
                ->groupBy('need')
                ->orderByDesc('total')
                ->get(),

            'byMonth' => $base()
                ->selectRaw("DATE_FORMAT(donated_at, '%Y-%m') as month, SUM(amount) as total, COUNT(*) as donations")
                ->groupBy('month')
                ->orderByDesc('month')
                ->limit(12)
                ->get(),
        ]);
    }

    // CSV download of the (filtered) donation list
    public function export(Request $request): StreamedResponse
    {
        $rows = $this->filtered($request)->latest('donated_at')->latest('id')->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // so Excel opens UTF-8 correctly
            fputcsv($out, ['Date', 'Aman ID', 'Donor', 'Email', 'Amount (USD)', 'For', 'Method', 'Reference', 'Note']);

            foreach ($rows as $d) {
                fputcsv($out, [
                    $d->donated_at->format('Y-m-d'),
                    $d->beneficiary?->aman_id,
                    $d->donor_name,
                    $d->donor_email,
                    $d->amount,
                    AmanDonation::needLabel($d->need),
                    AmanDonation::METHODS[$d->method] ?? $d->method,
                    $d->reference,
                    $d->note,
                ]);
            }

            fclose($out);
        }, 'aman-donations-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }
}