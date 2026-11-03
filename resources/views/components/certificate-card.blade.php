@props(['certificate', 'number'])
@php
    // Open the PDF when there is one, otherwise the certificate image.
    $pdf = $certificate->pdfUrl() ?? $certificate->previewUrl();
    $preview = $certificate->previewUrl();
    $verify = $certificate->verifyLink();
    $placeholders = (bool) config('portfolio.show_placeholders');
@endphp
<li class="card cert reveal" id="certificate-{{ $certificate->id }}">
    <div class="shot cert-shot">
        @if ($preview)
            <img src="{{ $preview }}" alt="Certificate: {{ $certificate->title }}, awarded to {{ config('portfolio.owner.name') }}" loading="lazy" decoding="async">
        @else
            <x-shot-art type="web" label="Certificate preview placeholder" />
        @endif
    </div>

    <div class="card-body">
        <div class="card-meta">
            <span class="code">CERT-{{ str_pad((string) $number, 2, '0', STR_PAD_LEFT) }}</span>
            <span class="badge"><x-icon name="shield" /><span>{{ $certificate->kind }}</span></span>
        </div>
        <h3>{{ $certificate->title }}</h3>
        <p class="desc">{{ $certificate->summary ?? 'Successfully completed the certification requirements for '.$certificate->title.'.' }}</p>

        <dl class="meta">
            @if ($certificate->program)
                <div class="wide"><dt>Program</dt><dd>{{ $certificate->program }}</dd></div>
            @endif
            <div class="wide"><dt>Issued by</dt><dd>{{ $certificate->issuer }}</dd></div>
            <div><dt>Date awarded</dt><dd>{{ $certificate->issued_on->format('F j, Y') }}</dd></div>
            @if ($certificate->credential_id)
                <div><dt>Verification code</dt><dd class="mono">{{ $certificate->credential_id }}</dd></div>
            @endif
        </dl>
        @if ($certificate->expiry_note)
            <p class="fine">{{ $certificate->expiry_note }}</p>
        @endif
    </div>

    <div class="card-foot">
        @if ($pdf)
            <a class="btn btn-primary" href="{{ $pdf }}" target="_blank" rel="noopener noreferrer"><x-icon name="doc" /><span>View certificate</span></a>
        @elseif ($placeholders)
            <span class="btn btn-ghost is-placeholder" aria-disabled="true"><x-icon name="doc" /><span>PDF to be added</span></span>
        @endif
        @if ($verify)
            <a class="btn btn-ghost" href="{{ $verify }}" target="_blank" rel="noopener noreferrer"><x-icon name="shield" /><span>Verify</span><x-icon name="ext" /></a>
        @endif
    </div>
</li>
