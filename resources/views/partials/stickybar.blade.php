@php
    $words = preg_split('/\s+/', trim($owner['name'])) ?: [];
    $initials = strtoupper(mb_substr($words[0] ?? '', 0, 1).mb_substr(end($words) ?: '', 0, 1));
@endphp
{{-- Appears after scrolling past the top, with the current section highlighted (portfolio.js). --}}
<nav class="stickybar" data-stickybar aria-label="Page sections">
    <div class="wrap stickybar-in">
        <a class="sb-brand" href="#top" aria-label="Back to top"><span class="monogram" aria-hidden="true">{{ $initials }}</span><span class="sb-name">{{ $owner['name'] }}</span></a>
        <span class="sb-links">
            <a href="#projects">Projects</a>
            <a href="#skills">Skills</a>
            <a href="#certifications">Certifications</a>
            <a href="#experience">Experience</a>
            <a href="#contact">Contact</a>
        </span>
    </div>
</nav>
