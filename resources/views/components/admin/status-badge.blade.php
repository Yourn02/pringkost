@props(['status'])

@php
    $status = strtolower($status ?? 'pending');
    $badgeConfig = match($status) {
        'approved', 'active' => [
            'bg' => 'bg-success-subtle',
            'text' => 'text-success',
            'border' => 'border-success-subtle',
            'icon' => 'fa-circle-check',
            'label' => $status === 'active' ? 'Aktif' : 'Disetujui',
        ],
        'pending' => [
            'bg' => 'bg-warning-subtle',
            'text' => 'text-warning-emphasis',
            'border' => 'border-warning-subtle',
            'icon' => 'fa-clock',
            'label' => 'Menunggu Verifikasi',
        ],
        'rejected' => [
            'bg' => 'bg-danger-subtle',
            'text' => 'text-danger',
            'border' => 'border-danger-subtle',
            'icon' => 'fa-circle-xmark',
            'label' => 'Ditolak',
        ],
        'suspended' => [
            'bg' => 'bg-warning-subtle',
            'text' => 'text-warning-emphasis',
            'border' => 'border-warning-subtle',
            'icon' => 'fa-triangle-exclamation',
            'label' => 'Ditangguhkan',
        ],
        'blocked' => [
            'bg' => 'bg-danger-subtle',
            'text' => 'text-danger',
            'border' => 'border-danger-subtle',
            'icon' => 'fa-ban',
            'label' => 'Diblokir',
        ],
        default => [
            'bg' => 'bg-secondary-subtle',
            'text' => 'text-secondary',
            'border' => 'border-secondary-subtle',
            'icon' => 'fa-circle-info',
            'label' => ucfirst($status),
        ]
    };
@endphp

<span {{ $attributes->merge(['class' => "badge {$badgeConfig['bg']} {$badgeConfig['text']} border {$badgeConfig['border']} px-2.5 py-1.5 rounded-pill fw-semibold d-inline-flex align-items-center gap-1.5 small"]) }}>
    <i class="fa-solid {{ $badgeConfig['icon'] }} fs-xs"></i>
    <span>{{ $badgeConfig['label'] }}</span>
</span>
