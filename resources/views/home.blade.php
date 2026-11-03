@extends('layouts.app')

@section('content')
@php
    $nameWords = preg_split('/\s+/', trim($owner['name'])) ?: [];
    $greetName = $owner['greeting_name'] ?? (count($nameWords) > 1 ? implode(' ', array_slice($nameWords, 0, -1)) : ($nameWords[0] ?? ''));
    $roleWords = preg_split('/\s+/', trim($owner['role']), 2) ?: [''];
    $roleFirst = $roleWords[0] ?? '';
    $roleRest = $roleWords[1] ?? '';
    $typedIntro = (bool) ($owner['typed_intro'] ?? true) && $roleRest !== '';
    $cv = trim((string) ($owner['cv'] ?? ''));
    $cvUrl = $cv !== '' && is_file(public_path($cv)) ? asset($cv) : null;
@endphp
<header class="hero" id="top">
    <div class="wrap">
        @include('partials.nav')

        <div class="hero-grid">
            <div class="hero-copy">
                <h1 class="hero-title" aria-label="{{ $owner['role'] }}"@if ($typedIntro) data-typewriter data-intro="{{ json_encode(['Hi, I’m', $greetName], JSON_UNESCAPED_UNICODE) }}"@endif>
                    <span class="line" aria-hidden="true"><span class="hl" data-tw-line>{{ $roleFirst }}</span></span>@if ($roleRest)<span class="line is-last" aria-hidden="true"><span data-tw-line>{{ $roleRest }}</span></span>@endif
                </h1>
                <p class="lede">{{ $owner['intro'] }}</p>
                <div class="hero-actions">
                    <a class="btn btn-light" href="#projects">View projects</a>
                    <a class="btn btn-line" href="#contact">Contact me</a>
                    @if ($cvUrl)
                        <a class="btn btn-line" href="{{ $cvUrl }}" download><x-icon name="doc" /><span>Download CV</span></a>
                    @endif
                </div>
            </div>

            <figure class="portrait">
                <div class="portrait-frame">
                    <img src="{{ asset($owner['photo']) }}" alt="{{ $owner['photo_alt'] }}" width="900" height="1053" fetchpriority="high">
                </div>
            </figure>
        </div>
    </div>
</header>

@include('partials.stickybar')

<main>
    <section class="section" id="projects" aria-labelledby="projects-title">
        <div class="wrap">
            <div class="section-head reveal">
                <div>
                    <p class="eyebrow">Projects</p>
                    <h2 id="projects-title">Selected work</h2>
                </div>
            </div>

            @if ($projects->isEmpty())
                <p class="ph-box">No projects are listed yet. Add one in config/projects.php.</p>
            @else
                <ul class="grid grid-3 grid-center">
                    @foreach ($projects as $project)
                        <x-project-card :project="$project" :number="$loop->iteration" />
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    @if ($skills->isNotEmpty())
        <section class="section section-tint compact" id="skills" aria-labelledby="skills-title">
            <div class="wrap">
                <div class="section-head reveal">
                    <div>
                        <p class="eyebrow">Skills</p>
                        <h2 id="skills-title">Tech skills</h2>
                    </div>
                    <p class="section-note">The technologies and tools I use to build, test and publish web and mobile applications.</p>
                </div>

                <ul class="skill-list skill-board reveal">
                    @foreach ($skills as $skill)
                        @php
                            $skillSlug = \App\Support\TechIcons::slug($skill['name']);
                            $skillBrand = $skillSlug && ! \App\Support\TechIcons::isDark($skillSlug) ? '#'.\App\Support\TechIcons::color($skillSlug) : null;
                        @endphp
                        <li class="skill" style="--i:{{ $loop->index }}{{ $skillBrand ? ';--brand-hover:'.$skillBrand : '' }}">
                            <x-tech-icon class="skill-logo" :name="\App\Support\TechIcons::slug($skill['name']) ? $skill['name'] : 'Blade'" />
                            <span class="skill-text">
                                <span class="skill-name">{{ $skill['name'] }}</span>
                                @if ($skill['note'])
                                    <span class="skill-note">{{ $skill['note'] }}</span>
                                @endif
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @if ($certificates->isNotEmpty() || $showPlaceholders)
        <section class="section" id="certifications" aria-labelledby="cert-title">
            <div class="wrap">
                <div class="section-head reveal">
                    <div>
                        <p class="eyebrow">Certifications</p>
                        <h2 id="cert-title">Credentials</h2>
                    </div>
                    <p class="section-note">Certifications earned so far. Open a certificate to view it in full, or use the verification code with the issuer.</p>
                </div>

                @if ($certificates->isEmpty())
                    <p class="ph-box">Certificates will appear here once they are added in config/certificates.php.</p>
                @else
                    <ul class="grid grid-3 grid-center">
                        @foreach ($certificates as $certificate)
                            <x-certificate-card :certificate="$certificate" :number="$loop->iteration" />
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>
    @endif

    @if ($experience->isNotEmpty())
        <section class="section section-tint" id="experience" aria-labelledby="exp-title">
            <div class="wrap">
                <div class="section-head reveal">
                    <div>
                        <p class="eyebrow">Experience</p>
                        <h2 id="exp-title">Work experience</h2>
                    </div>
                    <p class="section-note">Where I have worked and what the role involved.</p>
                </div>

                <ol class="timeline">
                    @foreach ($experience as $job)
                        <x-experience-item :job="$job" />
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    @include('partials.contact')
</main>
@endsection
