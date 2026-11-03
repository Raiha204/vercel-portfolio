<?php

namespace App\Support;

use App\Data\Certificate;
use App\Data\Experience;
use App\Data\Project;
use Illuminate\Support\Collection;

/** Reads the portfolio content from config/projects.php, config/certificates.php and config/experience.php. */
final class Portfolio
{
    /** @return Collection<int, Project> in the order they are listed in config/projects.php */
    public static function projects(): Collection
    {
        return collect(config('projects', []))
            ->filter(fn ($p) => is_array($p) && ($p['published'] ?? true) !== false && trim((string) ($p['title'] ?? '')) !== '')
            ->map(fn (array $p) => Project::fromArray($p))
            ->unique('slug')
            ->values();
    }

    public static function project(string $slug): ?Project
    {
        return self::projects()->firstWhere('slug', $slug);
    }

    /** @return Collection<int, Certificate> */
    public static function certificates(): Collection
    {
        return collect(config('certificates', []))
            ->filter(fn ($c) => is_array($c) && ($c['published'] ?? true) !== false && trim((string) ($c['title'] ?? '')) !== '' && ! empty($c['issued_on']))
            ->map(fn (array $c) => Certificate::fromArray($c))
            ->values();
    }

    /** @return Collection<int, Experience> */
    public static function experience(): Collection
    {
        return collect(config('experience', []))
            ->filter(fn ($e) => is_array($e) && ($e['published'] ?? true) !== false
                && trim((string) ($e['title'] ?? '')) !== '' && trim((string) ($e['company'] ?? '')) !== '' && ! empty($e['start']))
            ->map(fn (array $e) => Experience::fromArray($e))
            ->values();
    }

    /**
     * Skills from config/skills.php as one combined list, in the order listed.
     * (An older grouped format, ['group' => ..., 'skills' => [...]], is flattened into the same list.)
     *
     * @return Collection<int, array{name: string, note: ?string}>
     */
    public static function skills(): Collection
    {
        return collect(config('skills', []))
            ->flatMap(fn ($entry) => is_array($entry) && isset($entry['skills']) && is_array($entry['skills'])
                ? (($entry['published'] ?? true) === false ? [] : $entry['skills'])
                : [$entry])
            ->filter(fn ($s) => is_array($s) && ($s['published'] ?? true) !== false && trim((string) ($s['name'] ?? '')) !== '')
            ->map(function (array $s) {
                $note = trim((string) ($s['note'] ?? ''));

                return ['name' => trim((string) $s['name']), 'note' => $note !== '' ? $note : null];
            })
            ->unique(fn (array $s) => mb_strtolower($s['name']))
            ->values();
    }
}
