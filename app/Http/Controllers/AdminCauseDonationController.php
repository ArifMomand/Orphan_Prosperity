<?php

namespace App\Http\Controllers;

use App\Models\Cause;
use App\Models\CauseDonation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminCauseDonationController extends Controller
{
    // Shared filters for the list and the CSV export
    private function filtered(Request $request)
    {
        $q      = trim((string) $request->query('q', ''));
        $method = $request->query('method');
        $from   = $request->query('from');
        $to     = $request->query('to');

        return CauseDonation::query()
            ->with('cause')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('donor_name', 'like', "%{$q}%")
                      ->orWhere('donor_email', 'like', "%{$q}%")
                      ->orWhere('reference', 'like', "%{$q}%")
                      ->orWhereHas('cause', fn ($c) => $c->where('title', 'like', "%{$q}%"));
                });
            })
            ->when($method && isset(CauseDonation::METHODS[$method]), fn ($query) => $query->where('method', $method))
            ->when($from, fn ($query) => $query->whereDate('donated_at', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('donated_at', '<=', $to));
    }

    public function index(Request $request): View
    {
        $base = $this->filtered($request);

        return view('admin.cause-donations.index', [
            'items'  => (clone $base)->latest('donated_at')->latest('id')->paginate(20)->withQueryString(),
            'total'  => (clone $base)->sum('amount'),
            'count'  => (clone $base)->count(),
            'q'      => $request->query('q', ''),
            'method' => $request->query('method', ''),
            'from'   => $request->query('from', ''),
            'to'     => $request->query('to', ''),
        ]);
    }

    // One cause: its donations + the "record a donation" form
    public function forCause(Cause $cause): View
    {
        return view('admin.causes.donations', [
            'cause'     => $cause,
            'donations' => $cause->donations()->latest('donated_at')->latest('id')->get(),
        ]);
    }

    public function store(Request $request, Cause $cause): RedirectResponse
    {
        if ($cause->status !== 'approved') {
            return back()->with('success', 'Donations can only be recorded for approved causes.');
        }

        $data = $request->validate([
            'amount'      => ['required', 'numeric', 'min:0.01', 'max:1000000'],
            'donor_name'  => ['nullable', 'string', 'max:120'],
            'donor_email' => ['nullable', 'email', 'max:150'],
            'method'      => ['required', Rule::in(array_keys(CauseDonation::METHODS))],
            'reference'   => ['nullable', 'string', 'max:120'],
            'note'        => ['nullable', 'string', 'max:500'],
            'donated_at'  => ['required', 'date', 'before_or_equal:today'],
        ]);

        $cause->donations()->create($data + ['recorded_by' => Auth::id()]);

        return back()->with('success', 'Donation of $' . number_format($data['amount'], 2) . ' recorded for "' . $cause->title . '".');
    }

    public function destroy(CauseDonation $donation): RedirectResponse
    {
        $donation->delete();

        return back()->with('success', 'Donation removed.');
    }

    // Totals per cause, per method, per month
    public function report(Request $request): View
    {
        $from = $request->query('from');
        $to   = $request->query('to');

        $base = fn () => CauseDonation::query()
            ->when($from, fn ($q) => $q->whereDate('donated_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('donated_at', '<=', $to));

        return view('admin.cause-donations.report', [
            'from'          => $from,
            'to'            => $to,
            'grandTotal'    => $base()->sum('amount'),
            'grandCount'    => $base()->count(),
            'fundedCount'   => $base()->distinct()->count('cause_id'),
            'approvedCount' => Cause::where('status', 'approved')->count(),
            'openingTotal'  => Cause::sum('opening_raised'),

            'byCause' => $base()
                ->selectRaw('cause_id, SUM(amount) as total, COUNT(*) as donations')
                ->groupBy('cause_id')
                ->orderByDesc('total')
                ->with('cause')
                ->limit(50)
                ->get(),

            'byMethod' => $base()
                ->selectRaw('method, SUM(amount) as total, COUNT(*) as donations')
                ->groupBy('method')
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
            fputcsv($out, ['Date', 'Cause', 'Donor', 'Email', 'Amount (USD)', 'Method', 'Reference', 'Note']);

            foreach ($rows as $d) {
                fputcsv($out, [
                    $d->donated_at->format('Y-m-d'),
                    $d->cause?->title,
                    $d->donor_name,
                    $d->donor_email,
                    $d->amount,
                    CauseDonation::METHODS[$d->method] ?? $d->method,
                    $d->reference,
                    $d->note,
                ]);
            }

            fclose($out);
        }, 'cause-donations-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }
}