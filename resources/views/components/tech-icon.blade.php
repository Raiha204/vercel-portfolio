@props(['name'])
@php
    $slug = \App\Support\TechIcons::slug((string) $name);
    $color = $slug ? \App\Support\TechIcons::color($slug) : null;
@endphp
@if ($slug)
    <span {{ $attributes->class(['tech-icon', 'is-generic' => str_starts_with($slug, '_'), 'is-mono' => \App\Support\TechIcons::isDark($slug)])->merge(['style' => $color ? '--brand:#'.$color : null]) }}>{!! \App\Support\TechIcons::svg($slug) !!}</span>
@endif
