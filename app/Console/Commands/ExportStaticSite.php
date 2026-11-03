<?php

namespace App\Console\Commands;

use App\Http\Controllers\PortfolioController;
use App\Support\Portfolio;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class ExportStaticSite extends Command
{
    /** A host that never exists. Every link is generated against it, then rewritten to a root-relative link. */
    private const PLACEHOLDER_HOST = 'http://export.invalid';

    protected $signature = 'portfolio:export {--path=dist : Folder to write the site into (relative to the project, or absolute)}';

    protected $description = 'Render the whole portfolio to plain HTML files that any static host (such as Vercel) can serve';

    public function handle(PortfolioController $controller): int
    {
        $out = $this->outputPath((string) $this->option('path'));

        if ($out === null) {
            $this->error('Refusing to write there. Choose an empty or throwaway folder such as dist.');

            return self::FAILURE;
        }

        File::deleteDirectory($out);
        File::ensureDirectoryExists($out.'/projects');

        // Links and asset URLs are generated against a placeholder host, then made root-relative below.
        URL::forceRootUrl(self::PLACEHOLDER_HOST);
        URL::forceScheme('http');

        $pages = ['index.html' => $controller->index()->render()];

        foreach (Portfolio::projects() as $project) {
            $pages["projects/{$project->slug}.html"] = $controller->show($project->slug)->render();
        }

        $pages['404.html'] = view('errors.404')->render();

        foreach ($pages as $file => $html) {
            File::put("{$out}/{$file}", $this->makeRootRelative($html));
        }

        foreach (['css', 'js', 'images', 'certificates'] as $folder) {
            if (File::isDirectory(public_path($folder))) {
                File::copyDirectory(public_path($folder), "{$out}/{$folder}");
            }
        }
        foreach (File::allFiles($out, true) as $file) {
            if ($file->getFilename() === '.gitkeep') {
                File::delete($file->getPathname());
            }
        }
        foreach (['favicon.ico', 'robots.txt'] as $file) {
            if (File::exists(public_path($file))) {
                File::copy(public_path($file), "{$out}/{$file}");
            }
        }

        $this->info('Exported '.count($pages).' pages to '.$out);
        foreach (array_keys($pages) as $file) {
            $this->line('  '.$file);
        }

        return self::SUCCESS;
    }

    private function makeRootRelative(string $html): string
    {
        $host = self::PLACEHOLDER_HOST;

        // "…host#projects" must become "/#projects" (not "#projects", which would stay on the current page).
        return str_replace([$host.'#', $host.'"', $host.'/', $host], ['/#', '/"', '/', ''], $html);
    }

    /** Returns the absolute output folder, or null when the path could overwrite the project itself. */
    private function outputPath(string $path): ?string
    {
        $path = trim($path);

        if ($path === '') {
            return null;
        }

        $absolute = str_starts_with($path, '/') ? $path : base_path($path);
        $absolute = rtrim(str_replace('\\', '/', $absolute), '/');

        $protected = array_map(
            fn ($p) => rtrim(str_replace('\\', '/', realpath($p) ?: $p), '/'),
            [base_path(), public_path(), app_path(), config_path(), resource_path(), base_path('vendor'), base_path('routes'), storage_path(), dirname(base_path()), '/']
        );

        $resolved = rtrim(str_replace('\\', '/', realpath($absolute) ?: $absolute), '/');

        if ($resolved === '' || in_array($resolved, $protected, true) || str_contains($path, '..')) {
            return null;
        }

        // Anything inside the application folders (other than a plain output folder at the top) is refused too.
        foreach ([app_path(), config_path(), resource_path(), base_path('vendor'), base_path('routes'), public_path(), storage_path()] as $dir) {
            $dir = rtrim(str_replace('\\', '/', realpath($dir) ?: $dir), '/');
            if (str_starts_with($resolved.'/', $dir.'/')) {
                return null;
            }
        }

        return $absolute;
    }
}
