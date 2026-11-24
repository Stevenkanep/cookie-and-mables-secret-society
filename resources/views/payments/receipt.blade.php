<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt {{ $payment->receipt_number }} - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .receipt-card { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-4" style="max-width: 720px;">

    <div class="no-print d-flex justify-content-between mb-3">
        <a href="{{ route('transactions.show', $payment->transaction) }}" class="btn btn-outline-secondary">Back</a>
        <button type="button" class="btn btn-dark" onclick="window.print()">Print Receipt</button>
    </div>

    <div class="card shadow-sm receipt-card">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h1 class="h4 mb-1">{{ config('app.name') }}</h1>
                <div class="text-muted">Official Receipt</div>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <div>
                    <div class="text-muted small">Receipt No.</div>
                    <div class="fw-bold">{{ $payment->receipt_number }}</div>
                </div>
                <div class="text-end">
                    <div class="text-muted small">Date paid</div>
                    <div class="fw-bold">{{ $payment->paid_at->format('M d, Y') }}</div>
                </div>
            </div>

            <hr>

            <dl class="row mb-0">
                <dt class="col-sm-5">Member</dt>
                <dd class="col-sm-7">{{ $payment->transaction->member->full_name }}</dd>

                <dt class="col-sm-5">Membership No.</dt>
                <dd class="col-sm-7">{{ $payment->transaction->member->membership_number }}</dd>

                <dt class="col-sm-5">Transaction Ref.</dt>
                <dd class="col-sm-7">{{ $payment->transaction->reference_number }}</dd>

                <dt class="col-sm-5">Membership level</dt>
                <dd class="col-sm-7">{{ $payment->transaction->level->name }} ({{ ucfirst($payment->transaction->type) }})</dd>

                <dt class="col-sm-5">Payment method</dt>
                <dd class="col-sm-7">{{ $payment->method }}</dd>

                <dt class="col-sm-5">Notes</dt>
                <dd class="col-sm-7">{{ $payment->notes ?: '-' }}</dd>
            </dl>

            <hr>

            <div class="d-flex justify-content-between fs-5 fw-bold">
                <span>Amount paid</span>
                <span>₱{{ number_format($payment->amount, 2) }}</span>
            </div>

            <div class="d-flex justify-content-between text-muted mt-2">
                <span>Transaction total</span>
                <span>₱{{ number_format($payment->transaction->amount, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between text-muted">
                <span>Remaining balance</span>
                <span>₱{{ number_format((float) $payment->transaction->balance, 2) }}</span>
            </div>

            <p class="text-center text-muted small mt-4 mb-0">Thank you. Please keep this receipt for your records.</p>
        </div>
    </div>
</div>
</body>
</html>