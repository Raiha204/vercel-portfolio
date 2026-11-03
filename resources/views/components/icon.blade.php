@props(['name'])
<svg class="icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch($name)
        @case('web')
            <circle cx="8" cy="8" r="6.2"/><path d="M1.8 8h12.4M8 1.8c2.1 2.1 2.1 10.3 0 12.4M8 1.8c-2.1 2.1-2.1 10.3 0 12.4"/>
            @break
        @case('mobile')
            <rect x="4.2" y="1.5" width="7.6" height="13" rx="1.6"/><path d="M7 12.3h2"/>
            @break
        @case('desktop')
            <rect x="1.5" y="2.5" width="13" height="8.5" rx="1"/><path d="M5.5 14h5M8 11v3"/>
            @break
        @case('repo')
            <circle cx="4.5" cy="3.5" r="1.7"/><circle cx="4.5" cy="12.5" r="1.7"/><circle cx="11.5" cy="5.5" r="1.7"/><path d="M4.5 5.2v5.6M11.5 7.2c0 2.6-3.2 2.2-6 3.6"/>
            @break
        @case('ext')
            <path d="M6 3H3.5A1.5 1.5 0 0 0 2 4.5v8A1.5 1.5 0 0 0 3.5 14h8a1.5 1.5 0 0 0 1.5-1.5V10M9 2h5v5M14 2 7.5 8.5"/>
            @break
        @case('arrow-left')
            <path d="M10 3 5 8l5 5"/>
            @break
        @case('arrow-right')
            <path d="m6 3 5 5-5 5"/>
            @break
        @case('chat')
            <path d="M3 3h10a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H8.2L5 13.6V11H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1z"/>
            @break
        @case('phone')
            <path d="M3.4 2h2.3l1 2.8-1.4 1a7.6 7.6 0 0 0 3.6 3.6l1-1.4 2.8 1v2.3a1.5 1.5 0 0 1-1.6 1.5A10 10 0 0 1 2 3.6 1.5 1.5 0 0 1 3.4 2z"/>
            @break
        @case('mail')
            <rect x="1.8" y="3.2" width="12.4" height="9.6" rx="1.4"/><path d="m2.4 4.4 5.6 4.4 5.6-4.4"/>
            @break
        @case('doc')
            <path d="M4 1.8h5l3 3V14H4z"/><path d="M9 1.8v3h3M6 8h4M6 10.5h4"/>
            @break
        @case('shield')
            <path d="M8 1.8 13 3.6v4.1c0 3-2.1 5.3-5 6.5-2.9-1.2-5-3.5-5-6.5V3.6z"/><path d="m5.8 8 1.7 1.7 3-3.3"/>
            @break
        @case('copy')
            <rect x="5.5" y="5.5" width="8" height="8" rx="1.4"/><path d="M10.5 5.5V3.9a1.4 1.4 0 0 0-1.4-1.4H3.9a1.4 1.4 0 0 0-1.4 1.4v5.2a1.4 1.4 0 0 0 1.4 1.4h1.6"/>
            @break
        @case('check')
            <path d="m3.5 8.5 3 3 6-7"/>
            @break
        @default
            <rect x="2" y="2" width="12" height="12" rx="2" stroke-dasharray="2.2 2"/><path d="M6.3 6.4a1.7 1.7 0 1 1 2.4 1.5c-.5.3-.7.6-.7 1.2M8 11.4v.1"/>
    @endswitch
</svg>
