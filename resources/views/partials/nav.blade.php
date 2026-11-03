@php
    $words = preg_split('/\s+/', trim($owner['name'])) ?: [];
    $initials = strtoupper(mb_substr($words[0] ?? '', 0, 1).mb_substr(end($words) ?: '', 0, 1));
@endphp
<nav class="topbar" aria-label="Primary">
    <a class="brand" href="{{ route('home') }}" aria-label="{{ $owner['name'] }}, home">
        <span class="monogram" aria-hidden="true">{{ $initials }}</span>
        <span class="brand-name">{{ $owner['name'] }}</span>
    </a>
    <span class="topnav">
        <a href="{{ route('home') }}#projects">Projects</a>
        <a href="{{ route('home') }}#skills">Skills</a>
        <a href="{{ route('home') }}#certifications">Certifications</a>
        <a href="{{ route('home') }}#experience">Experience</a>
        <a href="{{ route('home') }}#contact">Contact</a>
    </span>
</nav>
