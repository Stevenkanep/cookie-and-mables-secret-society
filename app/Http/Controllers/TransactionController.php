<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipLevel;
use App\Models\MembershipTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $this->expireOverdue();

        $query = MembershipTransaction::with(['member', 'level']);

        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('member', function ($m) use ($search) {
                        $m->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('membership_number', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->query('type'));
        }

        $transactions = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('transactions.index', [
            'transactions' => $transactions,
            'statuses' => ['pending', 'paid', 'approved', 'expired', 'cancelled'],
        ]);
    }

    public function create(Request $request): View
    {
        return view('transactions.create', [
            'members' => Member::where('status', 'active')->orderBy('last_name')->orderBy('first_name')->get(),
            'levels' => MembershipLevel::where('is_active', true)->orderBy('fee')->get(),
            'selectedMember' => $request->query('member'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'member_id' => ['required', Rule::exists('members', 'id')->where('status', 'active')],
            'membership_level_id' => ['required', Rule::exists('membership_levels', 'id')->where('is_active', true)],
            'type' => ['required', Rule::in(['application', 'renewal'])],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $hasOpen = MembershipTransaction::where('member_id', $data['member_id'])
            ->whereIn('status', ['pending', 'paid'])
            ->exists();

        if ($hasOpen) {
            return back()->withInput()->withErrors([
                'member_id' => 'This member already has an open transaction (Pending or Paid). Finish or cancel it first.',
            ]);
        }

        $level = MembershipLevel::findOrFail($data['membership_level_id']);

        $transaction = MembershipTransaction::create([
            'member_id' => $data['member_id'],
            'membership_level_id' => $level->id,
            'type' => $data['type'],
            'amount' => $level->fee,
            'status' => $level->fee > 0 ? 'pending' : 'paid',
            'remarks' => $data['remarks'] ?? null,
        ]);

        $transaction->update([
            'reference_number' => 'TRX-' . now()->format('Y') . '-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT),
        ]);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction created. Reference: ' . $transaction->reference_number);
    }

    public function show(MembershipTransaction $transaction): View
    {
        $this->expireOverdue();

        $transaction->refresh()->load(['member', 'level', 'payments']);

        return view('transactions.show', compact('transaction'));
    }

    public function approve(MembershipTransaction $transaction): RedirectResponse
    {
        if ($transaction->status !== 'paid') {
            return back()->with('error', 'Only paid transactions can be approved.');
        }

        $transaction->load(['level', 'member']);

        $start = now()->startOfDay();
        $end = $start->copy()->addMonthsNoOverflow($transaction->level->validity_months);

        DB::transaction(function () use ($transaction, $start, $end) {
            $transaction->update([
                'status' => 'approved',
                'start_date' => $start,
                'end_date' => $end,
            ]);

            $transaction->member->update([
                'membership_level_id' => $transaction->membership_level_id,
                'status' => 'active',
            ]);
        });

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction approved. Membership runs until ' . $end->format('M d, Y') . '.');
    }

    public function cancel(MembershipTransaction $transaction): RedirectResponse
    {
        if ($transaction->status !== 'pending' || $transaction->payments()->exists()) {
            return back()->with('error', 'Only unpaid pending transactions can be cancelled.');
        }

        $transaction->update(['status' => 'cancelled']);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction cancelled.');
    }

    public function history(Member $member): View
    {
        $this->expireOverdue();

        $member->load(['category', 'level']);
        $transactions = $member->transactions()->with('level')->orderByDesc('id')->get();

        return view('transactions.history', compact('member', 'transactions'));
    }

    private function expireOverdue(): void
    {
        MembershipTransaction::where('status', 'approved')
            ->whereDate('end_date', '<', now()->toDateString())
            ->update(['status' => 'expired']);
    }
}