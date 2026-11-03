@props(['type' => null, 'label' => 'Screenshot placeholder'])
@php
    $kind = in_array($type, ['web', 'mobile', 'desktop'], true) ? $type : 'unknown';
@endphp
{{-- Wireframe placeholder, one per application type. Shapes stay between y=40 and y=176 so the badges above and the label below never overlap them. --}}
<svg class="art" viewBox="0 0 320 200" preserveAspectRatio="xMidYMid meet" role="img" aria-label="{{ $label }}">
    @if ($kind === 'web')
        <rect class="ps" pathLength="1" style="--k:0" x="40" y="40" width="240" height="136" rx="6"/>
        <path class="ps" pathLength="1" style="--k:1" d="M40 58h240"/>
        <circle class="pf" style="--k:2" cx="52" cy="49" r="2.6"/><circle class="pf" style="--k:3" cx="62" cy="49" r="2.6"/><circle class="pf" style="--k:4" cx="72" cy="49" r="2.6"/>
        <rect class="pf" style="--k:5" x="54" y="70" width="90" height="9" rx="2"/>
        <rect class="pf2" style="--k:6" x="54" y="86" width="140" height="6" rx="2"/>
        <rect class="ps" pathLength="1" style="--k:7" x="54" y="104" width="64" height="58" rx="4"/>
        <rect class="ps" pathLength="1" style="--k:8" x="128" y="104" width="64" height="58" rx="4"/>
        <rect class="ps" pathLength="1" style="--k:9" x="202" y="104" width="64" height="58" rx="4"/>
    @elseif ($kind === 'mobile')
        <rect class="ps" pathLength="1" style="--k:0" x="122" y="38" width="76" height="140" rx="12"/>
        <rect class="pf" style="--k:1" x="149" y="44" width="22" height="4" rx="2"/>
        <rect class="pf" style="--k:2" x="132" y="58" width="56" height="8" rx="2"/>
        <rect class="ps" pathLength="1" style="--k:3" x="132" y="73" width="56" height="34" rx="4"/>
        <rect class="pf2" style="--k:4" x="132" y="115" width="56" height="5" rx="2"/>
        <rect class="pf2" style="--k:5" x="132" y="126" width="40" height="5" rx="2"/>
        <rect class="ps" pathLength="1" style="--k:6" x="132" y="141" width="26" height="26" rx="4"/>
        <rect class="ps" pathLength="1" style="--k:7" x="162" y="141" width="26" height="26" rx="4"/>
    @elseif ($kind === 'desktop')
        <rect class="ps" pathLength="1" style="--k:0" x="40" y="40" width="240" height="108" rx="6"/>
        <path class="ps" pathLength="1" style="--k:1" d="M40 56h240M92 56v92"/>
        <circle class="pf" style="--k:2" cx="51" cy="48" r="2.6"/><circle class="pf" style="--k:3" cx="60" cy="48" r="2.6"/>
        <rect class="pf" style="--k:4" x="50" y="66" width="34" height="5" rx="2"/>
        <rect class="pf2" style="--k:5" x="50" y="78" width="26" height="5" rx="2"/>
        <rect class="pf2" style="--k:6" x="50" y="90" width="30" height="5" rx="2"/>
        <rect class="pf" style="--k:7" x="104" y="66" width="80" height="7" rx="2"/>
        <path class="ps" pathLength="1" style="--k:8" d="M104 88h164M104 105h164M104 122h164M104 139h164"/>
        <path class="ps" pathLength="1" style="--k:9" d="M142 148v18M120 166h80"/>
    @else
        <rect class="ps" pathLength="1" style="--k:0" x="40" y="40" width="240" height="136" rx="6"/>
        <rect class="pf" style="--k:1" x="54" y="54" width="100" height="9" rx="2"/>
        <rect class="pf2" style="--k:2" x="54" y="70" width="150" height="6" rx="2"/>
        <rect class="ps" pathLength="1" style="--k:3" x="54" y="92" width="64" height="70" rx="4"/>
        <rect class="ps" pathLength="1" style="--k:4" x="128" y="92" width="64" height="70" rx="4"/>
        <rect class="ps" pathLength="1" style="--k:5" x="202" y="92" width="64" height="70" rx="4"/>
    @endif
    <text class="art-label" x="160" y="196" text-anchor="middle">{{ strtoupper($label) }}</text>
</svg>
