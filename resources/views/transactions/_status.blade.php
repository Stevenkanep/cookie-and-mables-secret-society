@php
    $colors = [
        'pending' => 'warning',
        'paid' => 'info',
        'approved' => 'success',
        'expired' => 'secondary',
        'cancelled' => 'danger',
    ];
@endphp
<span class="badge text-bg-{{ $colors[$status] ?? 'secondary' }}">{{ ucfirst($status) }}</span>