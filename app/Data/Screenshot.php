<?php

namespace App\Data;

/** One screenshot of a project. `src` is a file path inside public/, e.g. images/projects/max-gym-dashboard.png. */
final class Screenshot
{
    public function __construct(
        public readonly string $src,
        public readonly ?string $alt = null,
        public readonly ?string $caption = null,
    ) {
    }

    /** True for tall images such as phone screenshots, so the page can show them in a portrait frame. */
    public function isPortrait(): bool
    {
        $size = @getimagesize(public_path(ltrim($this->src, '/')));

        return $size !== false && $size[1] > $size[0];
    }

    public function url(): string
    {
        return asset(ltrim($this->src, '/'));
    }
}
