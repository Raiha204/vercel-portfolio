<?php

namespace App\Support;

/**
 * Logos for technologies, from the Simple Icons set (CC0) and, for C# and Java, the Devicon set (MIT).
 * The logos are trademarks of their owners.
 * The SVG files are in resources/svg/tech/. To show a logo for a new technology, add its SVG there,
 * then add its name below (lower case) and its brand colour.
 */
final class TechIcons
{
    /** Lower-case technology name => icon file name (without .svg). */
    private const ALIASES = [
        'html' => 'html5', 'html5' => 'html5', 'css' => 'css', 'css3' => 'css',
        'tailwind css' => 'tailwindcss', 'tailwind' => 'tailwindcss',
        'react' => 'react', 'typescript' => 'typescript', 'javascript' => 'javascript', 'js' => 'javascript',
        'alpine.js' => 'alpinedotjs', 'alpinejs' => 'alpinedotjs',
        'php' => 'php', 'laravel' => 'laravel', 'inertia.js' => 'inertia', 'inertia' => 'inertia',
        'mysql' => 'mysql', 'mariadb' => 'mariadb', 'postgresql' => 'postgresql', 'sqlite' => 'sqlite', 'mongodb' => 'mongodb',
        'vite' => 'vite', 'github' => 'github', 'git' => 'git', 'npm' => 'npm', 'composer' => 'composer',
        'python' => 'python', 'flutter' => 'flutter', 'dart' => 'dart',
        'google sign-in' => 'google', 'google' => 'google',
        'cloud firestore' => 'firebase', 'firebase authentication' => 'firebase', 'firebase' => 'firebase',
        'node.js' => 'nodedotjs', 'nodejs' => 'nodedotjs', 'vue' => 'vuedotjs', 'vue.js' => 'vuedotjs',
        'bootstrap' => 'bootstrap', 'sass' => 'sass', 'jquery' => 'jquery', 'docker' => 'docker', 'figma' => 'figma',
        'linux' => 'linux', 'c' => 'c', 'c++' => 'cplusplus', 'postman' => 'postman', 'xampp' => 'xampp',
        'nginx' => 'nginx', 'apache' => 'apache',
        // No official logo: a neutral glyph is shown instead.
        'c#' => 'csharp', 'csharp' => 'csharp', 'java' => 'java',
        'vercel' => 'vercel', 'deployment & hosting' => 'vercel', 'ui/ux design' => 'figma', 'ui/ux' => 'figma',
        'rest apis' => '_api', 'rest api' => '_api', 'api' => '_api',
        'blade' => '_code', 'databases' => '_database', 'database' => '_database',
    ];

    /** Brand colours (hex, no #). */
    private const COLORS = [
        'html5' => 'E34F26',
        'css' => '663399',
        'tailwindcss' => '06B6D4',
        'react' => '61DAFB',
        'typescript' => '3178C6',
        'alpinedotjs' => '8BC0D0',
        'php' => '777BB4',
        'laravel' => 'FF2D20',
        'inertia' => '9553E9',
        'mysql' => '4479A1',
        'vite' => '9135FF',
        'github' => '181717',
        'vercel' => '000000',
        'csharp' => '9B4F96',
        'java' => 'EA2D2E',
        'javascript' => 'F7DF1E',
        'python' => '3776AB',
        'flutter' => '02569B',
        'google' => '4285F4',
        'dart' => '0175C2',
        'firebase' => 'DD2C00',
        'git' => 'F03C2E',
        'nodedotjs' => '5FA04E',
        'vuedotjs' => '4FC08D',
        'bootstrap' => '7952B3',
        'postgresql' => '4169E1',
        'sqlite' => '003B57',
        'mariadb' => '003545',
        'docker' => '2496ED',
        'npm' => 'CB3837',
        'composer' => '885630',
        'figma' => 'F24E1E',
        'linux' => 'FCC624',
        'c' => 'A8B9CC',
        'cplusplus' => '00599C',
        'kotlin' => '7F52FF',
        'sass' => 'CC6699',
        'jquery' => '0769AD',
        'mongodb' => '47A248',
        'postman' => 'FF6C37',
        'xampp' => 'FB7A24',
        'nginx' => '009639',
        'apache' => 'D22128',
    ];

    public static function slug(string $name): ?string
    {
        $slug = self::ALIASES[mb_strtolower(trim($name))] ?? null;

        return $slug !== null && is_file(self::path($slug)) ? $slug : null;
    }

    public static function color(string $slug): ?string
    {
        return self::COLORS[$slug] ?? null;
    }

    /** True for logos whose brand colour is nearly black, which would vanish on a dark background. */
    public static function isDark(string $slug): bool
    {
        $hex = self::COLORS[$slug] ?? null;
        if ($hex === null) {
            return false;
        }
        [$r, $g, $b] = array_map('hexdec', str_split($hex, 2));

        return (0.2126 * $r + 0.7152 * $g + 0.0722 * $b) / 255 < 0.2;
    }

    public static function svg(string $slug): string
    {
        return trim((string) file_get_contents(self::path($slug)));
    }

    private static function path(string $slug): string
    {
        return resource_path('svg/tech/'.preg_replace('/[^a-z0-9_]/', '', $slug).'.svg');
    }
}
