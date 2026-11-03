<?php

namespace App\Data;

use App\Support\Url;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/** A portfolio project, built from one entry of config/projects.php. */
final class Project
{
    /** Application types the portfolio can show. */
    public const TYPES = [
        'web' => 'Web application',
        'mobile' => 'Mobile application',
        'desktop' => 'Desktop application',
    ];

    /**
     * @param  list<string>  $features
     * @param  list<string>  $technologies
     * @param  Collection<int, Screenshot>  $screenshots
     */
    public function __construct(
        public readonly string $slug,
        public readonly string $title,
        public readonly ?string $type,
        public readonly ?string $summary,
        public readonly ?string $problem,
        public readonly ?string $solution,
        public readonly array $features,
        public readonly array $technologies,
        public readonly ?string $contribution,
        public readonly ?string $github_url,
        public readonly ?string $demo_url,
        public readonly Collection $screenshots,
        public readonly ?string $card_summary = null,
        public readonly ?string $card_problem = null,
    ) {
    }

    /** @param  array<string, mixed>  $data */
    public static function fromArray(array $data): self
    {
        $title = trim((string) ($data['title'] ?? ''));
        $type = $data['type'] ?? null;

        return new self(
            slug: Str::slug((string) ($data['slug'] ?? '')) ?: (Str::slug($title) ?: 'project'),
            title: $title,
            type: is_string($type) && isset(self::TYPES[$type]) ? $type : null,
            summary: self::text($data['summary'] ?? null),
            problem: self::text($data['problem'] ?? null),
            solution: self::text($data['solution'] ?? null),
            features: self::cleanList($data['features'] ?? []),
            technologies: self::cleanList($data['technologies'] ?? []),
            contribution: self::text($data['contribution'] ?? null),
            github_url: self::text($data['github_url'] ?? null),
            demo_url: self::text($data['demo_url'] ?? null),
            screenshots: collect($data['screenshots'] ?? [])
                ->filter(fn ($s) => is_array($s) && trim((string) ($s['src'] ?? '')) !== '')
                ->map(fn (array $s) => new Screenshot(trim($s['src']), self::text($s['alt'] ?? null), self::text($s['caption'] ?? null)))
                ->values(),
            card_summary: self::text($data['card_summary'] ?? null),
            card_problem: self::text($data['card_problem'] ?? null),
        );
    }

    /** The short description for the card (falls back to the full summary). */
    public function cardSummary(): ?string
    {
        return $this->card_summary ?? $this->summary;
    }

    /** The short problem statement for the card (falls back to the full text). */
    public function cardProblem(): ?string
    {
        return $this->card_problem ?? $this->problem;
    }

    public function typeLabel(): ?string
    {
        return self::TYPES[$this->type] ?? null;
    }

    /** The repository link, only when it is a real http(s) address. */
    public function githubLink(): ?string
    {
        return Url::safe($this->github_url);
    }

    /** The live demo link, only when it is a real http(s) address. */
    public function demoLink(): ?string
    {
        return Url::safe($this->demo_url);
    }

    /** @return list<string> */
    public function featureList(): array
    {
        return $this->features;
    }

    /** @return list<string> */
    public function techList(): array
    {
        return $this->technologies;
    }

    private static function text(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /** @return list<string> */
    private static function cleanList(mixed $items): array
    {
        return array_values(array_filter(
            array_map(fn ($item) => trim((string) $item), is_array($items) ? $items : []),
            fn ($item) => $item !== ''
        ));
    }
}
