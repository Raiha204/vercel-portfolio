<?php

namespace App\Data;

use Carbon\CarbonImmutable;

/** A job, built from one entry of config/experience.php. */
final class Experience
{
    public function __construct(
        public readonly string $title,
        public readonly string $company,
        public readonly ?string $employment_type,
        public readonly CarbonImmutable $start,
        public readonly ?CarbonImmutable $end,
        public readonly ?string $location,
        public readonly ?string $work_mode,
        public readonly ?string $description,
    ) {
    }

    /** @param  array<string, mixed>  $data */
    public static function fromArray(array $data): self
    {
        $text = fn ($v) => ($v = trim((string) $v)) === '' ? null : $v;
        $month = fn ($v) => ($v = trim((string) $v)) === '' ? null : CarbonImmutable::createFromFormat('!Y-m', $v);

        return new self(
            title: trim((string) ($data['title'] ?? '')),
            company: trim((string) ($data['company'] ?? '')),
            employment_type: $text($data['employment_type'] ?? null),
            start: $month($data['start'] ?? null) ?? CarbonImmutable::now()->startOfMonth(),
            end: $month($data['end'] ?? null),
            location: $text($data['location'] ?? null),
            work_mode: $text($data['work_mode'] ?? null),
            description: $text($data['description'] ?? null),
        );
    }

    public function isCurrent(): bool
    {
        return $this->end === null;
    }

    /** "Jun 2025 – Nov 2025" or "Jun 2025 – Present". */
    public function period(): string
    {
        return $this->start->format('M Y').' – '.($this->end ? $this->end->format('M Y') : 'Present');
    }

    /** "6 mos", "1 yr 2 mos". Both the first and the last month count, as on LinkedIn. */
    public function duration(): string
    {
        $months = $this->start->diffInMonths(($this->end ?? CarbonImmutable::now())->startOfMonth()) + 1;
        $months = (int) max(1, floor($months));
        $years = intdiv($months, 12);
        $rest = $months % 12;

        $parts = [];
        if ($years > 0) {
            $parts[] = $years.' '.($years === 1 ? 'yr' : 'yrs');
        }
        if ($rest > 0) {
            $parts[] = $rest.' '.($rest === 1 ? 'mo' : 'mos');
        }

        return implode(' ', $parts);
    }
}
