<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipTransaction;
use App\Models\Payment;
use App\Services\WeatherService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(WeatherService $weather): View
    {
        MembershipTransaction::where('status', 'approved')
            ->whereDate('end_date', '<', now()->toDateString())
            ->update(['status' => 'expired']);

        return view('dashboard', [
            'totalMembers' => Member::count(),
            'activeMembers' => Member::where('status', 'active')->count(),
            'pendingTransactions' => MembershipTransaction::where('status', 'pending')->count(),
            'collectedPayments' => (float) Payment::sum('amount'),
            'recentTransactions' => MembershipTransaction::with(['member', 'level'])
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
            'weather' => $weather->current(),
        ]);
    }
}