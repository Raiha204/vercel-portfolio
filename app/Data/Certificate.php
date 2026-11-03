<?php

namespace App\Data;

use App\Support\Url;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

/** A certificate, built from one entry of config/certificates.php. */
final class Certificate
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly ?string $program,
        public readonly ?string $issuer,
        public readonly CarbonImmutable $issued_on,
        public readonly ?string $credential_id,
        public readonly ?string $verify_url,
        public readonly ?string $expiry_note,
        public readonly ?string $pdf,
        public readonly ?string $preview,
        public readonly string $kind = 'Certification',
        public readonly ?string $summary = null,
    ) {
    }

    /** @param  array<string, mixed>  $data */
    public static function fromArray(array $data): self
    {
        $title = trim((string) ($data['title'] ?? ''));
        $text = fn ($v) => ($v = trim((string) $v)) === '' ? null : $v;

        return new self(
            id: Str::slug($title.' '.($data['credential_id'] ?? '')) ?: 'certificate',
            title: $title,
            program: $text($data['program'] ?? null),
            issuer: $text($data['issuer'] ?? null),
            issued_on: CarbonImmutable::parse((string) $data['issued_on']),
            credential_id: $text($data['credential_id'] ?? null),
            verify_url: $text($data['verify_url'] ?? null),
            expiry_note: $text($data['expiry_note'] ?? null),
            pdf: $text($data['pdf'] ?? null),
            preview: $text($data['preview'] ?? null),
            kind: $text($data['kind'] ?? null) ?? 'Certification',
            summary: $text($data['summary'] ?? null),
        );
    }

    public function pdfUrl(): ?string
    {
        return $this->pdf ? asset(ltrim($this->pdf, '/')) : null;
    }

    public function previewUrl(): ?string
    {
        return $this->preview ? asset(ltrim($this->preview, '/')) : null;
    }

    public function verifyLink(): ?string
    {
        return Url::safe($this->verify_url);
    }
}
