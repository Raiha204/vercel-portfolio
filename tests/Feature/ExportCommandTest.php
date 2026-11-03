<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ExportCommandTest extends TestCase
{
    private string $out;

    protected function setUp(): void
    {
        parent::setUp();
        $this->out = sys_get_temp_dir().'/portfolio-export-'.bin2hex(random_bytes(4));
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->out);
        parent::tearDown();
    }

    public function test_it_writes_every_page_and_the_assets(): void
    {
        $this->artisan('portfolio:export', ['--path' => $this->out])->assertSuccessful();

        foreach ([
            'index.html', '404.html',
            'projects/max-gym-integrated-management-system.html',
            'projects/reading-habit-analytics.html',
            'projects/farm-supply-inventory-management-system.html',
            'projects/grocery-store-point-of-sale-system.html',
            'css/portfolio.css', 'js/portfolio.js', 'images/profile.jpg',
            'certificates/it-specialist-html-and-css.pdf', 'certificates/it-specialist-databases.jpg',
        ] as $file) {
            $this->assertFileExists("{$this->out}/{$file}", "{$file} was not exported");
        }

        $this->assertFileDoesNotExist("{$this->out}/index.php");
        $this->assertFileDoesNotExist("{$this->out}/.htaccess");
    }

    public function test_links_are_root_relative_and_work_from_any_page(): void
    {
        $this->artisan('portfolio:export', ['--path' => $this->out])->assertSuccessful();

        $home = file_get_contents("{$this->out}/index.html");
        $detail = file_get_contents("{$this->out}/projects/reading-habit-analytics.html");

        foreach ([$home, $detail, file_get_contents("{$this->out}/404.html")] as $html) {
            $this->assertStringNotContainsString('export.invalid', $html);
            $this->assertStringNotContainsString('127.0.0.1', $html);
            $this->assertStringContainsString('href="/css/portfolio.css"', $html);
            $this->assertStringContainsString('src="/js/portfolio.js"', $html);
        }

        $this->assertStringContainsString('href="/projects/reading-habit-analytics"', $home);
        $this->assertStringContainsString('href="/#projects"', $detail);  // back link must leave the details page
        $this->assertStringContainsString('src="/images/profile.jpg"', $home);
        $this->assertStringContainsString('href="/certificates/it-specialist-html-and-css.pdf"', $home);
        $this->assertStringContainsString('https://wa.me/639705476036', $home);
    }

    public function test_it_replaces_an_old_export_instead_of_mixing_with_it(): void
    {
        File::ensureDirectoryExists($this->out);
        File::put("{$this->out}/stale.html", 'old');

        $this->artisan('portfolio:export', ['--path' => $this->out])->assertSuccessful();

        $this->assertFileDoesNotExist("{$this->out}/stale.html");
    }

    public function test_it_refuses_paths_that_could_overwrite_the_project(): void
    {
        foreach (['', '/', '.', 'app', 'public', 'config', '../outside', base_path(), base_path('vendor')] as $path) {
            $this->artisan('portfolio:export', ['--path' => $path])->assertFailed();
        }

        $this->assertFileExists(base_path('config/projects.php'));
        $this->assertFileExists(public_path('css/portfolio.css'));
    }
}
