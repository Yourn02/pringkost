@props([
    'title',
    'value',
    'icon' => 'fa-chart-simple',
    'color' => 'burnt', // burnt, emerald, amber, blue, purple, dark
    'link' => null,
    'description' => null,
    'badge' => null,
])

@php
    $themeMap = [
        'burnt' => [
            'icon_bg' => 'rgba(217, 84, 30, 0.12)',
            'icon_color' => '#D9541E',
            'border' => 'rgba(217, 84, 30, 0.25)',
        ],
        'emerald' => [
            'icon_bg' => 'rgba(5, 150, 105, 0.12)',
            'icon_color' => '#059669',
            'border' => 'rgba(5, 150, 105, 0.25)',
        ],
        'amber' => [
            'icon_bg' => 'rgba(217, 119, 6, 0.12)',
            'icon_color' => '#D97706',
            'border' => 'rgba(217, 119, 6, 0.25)',
        ],
        'blue' => [
            'icon_bg' => 'rgba(37, 99, 235, 0.12)',
            'icon_color' => '#2563EB',
            'border' => 'rgba(37, 99, 235, 0.25)',
        ],
        'purple' => [
            'icon_bg' => 'rgba(124, 58, 237, 0.12)',
            'icon_color' => '#7C3AED',
            'border' => 'rgba(124, 58, 237, 0.25)',
        ],
        'dark' => [
            'icon_bg' => 'rgba(17, 17, 17, 0.08)',
            'icon_color' => '#111111',
            'border' => 'rgba(17, 17, 17, 0.15)',
        ],
    ];

    $currentTheme = $themeMap[$color] ?? $themeMap['burnt'];
@endphp

<div class="card stat-card border-0 shadow-sm rounded-4 h-100 p-3 bg-white position-relative overflow-hidden transition-all">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="text-secondary fw-semibold small text-uppercase tracking-wider">{{ $title }}</span>
        <div class="stat-icon-wrapper rounded-3 d-flex align-items-center justify-content-center" 
             style="width: 42px; height: 42px; background-color: {{ $currentTheme['icon_bg'] }}; color: {{ $currentTheme['icon_color'] }};">
            <i class="fa-solid {{ $icon }} fs-5"></i>
        </div>
    </div>
    
    <div class="d-flex align-items-baseline gap-2 mb-1">
        <h3 class="fw-bold mb-0 text-dark">{{ is_numeric($value) ? number_format($value, 0, ',', '.') : $value }}</h3>
        @if($badge)
            <span class="badge bg-light text-dark border small">{{ $badge }}</span>
        @endif
    </div>

    @if($description)
        <p class="text-muted small mb-3">{{ $description }}</p>
    @else
        <div class="mb-3"></div>
    @endif

    @if($link)
        <a href="{{ $link }}" class="small fw-bold text-decoration-none d-inline-flex align-items-center gap-1 mt-auto pt-2 border-top border-light" style="color: {{ $currentTheme['icon_color'] }};">
            <span>Lihat Detail</span>
            <i class="fa-solid fa-arrow-right fs-xs"></i>
        </a>
    @endif
</div>
