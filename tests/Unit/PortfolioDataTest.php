<?php

namespace Tests\Unit;

use App\Data\Certificate;
use App\Data\Project;
use App\Support\Portfolio;
use Tests\TestCase;

class PortfolioDataTest extends TestCase
{
    public function test_a_project_is_cleaned_when_it_is_built(): void
    {
        $project = Project::fromArray([
            'title' => '  My Tool ',
            'type' => 'console',
            'summary' => '   ',
            'features' => ['One', '  ', ' Two '],
            'technologies' => 'not a list',
            'github_url' => 'javascript:alert(1)',
            'demo_url' => 'https://example.test/demo',
            'screenshots' => [['src' => 'images/projects/a.png'], ['src' => ' '], 'nonsense'],
        ]);

        $this->assertSame('My Tool', $project->title);
        $this->assertSame('my-tool', $project->slug);
        $this->assertNull($project->type);          // unknown application types are dropped
        $this->assertNull($project->typeLabel());
        $this->assertNull($project->summary);       // blank text becomes "missing"
        $this->assertSame(['One', 'Two'], $project->featureList());
        $this->assertSame([], $project->techList());
        $this->assertNull($project->githubLink());  // unsafe link is never offered
        $this->assertSame('https://example.test/demo', $project->demoLink());
        $this->assertCount(1, $project->screenshots);
    }

    public function test_known_types_have_labels(): void
    {
        foreach (['web' => 'Web application', 'mobile' => 'Mobile application', 'desktop' => 'Desktop application'] as $type => $label) {
            $this->assertSame($label, Project::fromArray(['title' => 'X', 'type' => $type])->typeLabel());
        }
    }

    public function test_a_certificate_reads_its_date_and_links(): void
    {
        $certificate = Certificate::fromArray([
            'title' => 'Databases',
            'issued_on' => '2026-05-14',
            'credential_id' => 'abc-123',
            'verify_url' => 'ftp://nope.test',
        ]);

        $this->assertSame('May 14, 2026', $certificate->issued_on->format('F j, Y'));
        $this->assertNull($certificate->verifyLink());
        $this->assertNull($certificate->pdf);
        $this->assertSame('databases-abc-123', $certificate->id);
    }

    public function test_experience_shows_the_period_and_a_linkedin_style_duration(): void
    {
        $job = \App\Data\Experience::fromArray(['title' => 'A', 'company' => 'B', 'start' => '2025-06', 'end' => '2025-11']);
        $this->assertSame('Jun 2025 – Nov 2025', $job->period());
        $this->assertSame('6 mos', $job->duration());
        $this->assertFalse($job->isCurrent());

        $long = \App\Data\Experience::fromArray(['title' => 'A', 'company' => 'B', 'start' => '2023-01', 'end' => '2024-02']);
        $this->assertSame('1 yr 2 mos', $long->duration());

        $one = \App\Data\Experience::fromArray(['title' => 'A', 'company' => 'B', 'start' => '2025-06', 'end' => '2025-06']);
        $this->assertSame('1 mo', $one->duration());

        $current = \App\Data\Experience::fromArray(['title' => 'A', 'company' => 'B', 'start' => '2025-06']);
        $this->assertTrue($current->isCurrent());
        $this->assertSame('Jun 2025 – Present', $current->period());
    }

    public function test_incomplete_or_hidden_experience_entries_are_skipped(): void
    {
        config(['experience' => [
            ['title' => 'Kept', 'company' => 'Co', 'start' => '2025-01', 'end' => '2025-02'],
            ['title' => 'No company', 'start' => '2025-01'],
            ['title' => 'Hidden', 'company' => 'Co', 'start' => '2025-01', 'published' => false],
            'nonsense',
        ]]);

        $this->assertSame(['Kept'], Portfolio::experience()->pluck('title')->all());
    }
}
