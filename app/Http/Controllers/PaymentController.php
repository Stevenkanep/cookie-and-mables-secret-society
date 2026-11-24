<?php

namespace App\Http\Controllers;

use App\Models\MembershipTransaction;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public const METHODS = ['Cash', 'GCash', 'Bank Transfer', 'Credit/Debit Card'];

    public function create(MembershipTransaction $transaction): View|RedirectResponse
    {
        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Payments can only be recorded for pending transactions.');
        }

        $transaction->load(['member', 'level']);

        return view('payments.create', [
            'transaction' => $transaction,
            'methods' => self::METHODS,
        ]);
    }

    public function store(Request $request, MembershipTransaction $transaction): RedirectResponse
    {
        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Payments can only be recorded for pending transactions.');
        }

        $balance = (float) $transaction->balance;

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $balance],
            'method' => ['required', Rule::in(self::METHODS)],
            'paid_at' => ['required', 'date', 'before_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'amount.max' => 'The payment cannot be more than the remaining balance of ₱' . number_format($balance, 2) . '.',
        ]);

        $payment = DB::transaction(function () use ($transaction, $data) {
            $payment = $transaction->payments()->create($data);

            $payment->update([
                'receipt_number' => 'OR-' . now()->format('Y') . '-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT),
            ]);

            $totalPaid = (float) $transaction->payments()->sum('amount');

            if (round((float) $transaction->amount - $totalPaid, 2) <= 0) {
                $transaction->update(['status' => 'paid']);
            }

            return $payment;
        });

        $message = 'Payment recorded. Receipt: ' . $payment->receipt_number . '.';

        if ($transaction->fresh()->status === 'paid') {
            $message .= ' The transaction is now fully paid and can be approved.';
        }

        return redirect()->route('transactions.show', $transaction)
            ->with('success', $message);
    }

    public function receipt(Payment $payment): View
    {
        $payment->load(['transaction.member', 'transaction.level']);

        return view('payments.receipt', compact('payment'));
    }
}