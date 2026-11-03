@extends('layouts.app')

@section('title', $project->title.' · '.$owner['name'])
@section('description', \Illuminate\Support\Str::limit($project->summary, 155))

@section('content')
@php
    $features = $project->featureList();
    $tech = $project->techList();
    $github = $project->githubLink();
    $demo = $project->demoLink();
    $shots = $project->screenshots;
    $type = $project->typeLabel();
@endphp

<header class="hero hero-compact">
    <div class="wrap">
        @include('partials.nav')

        <div class="detail-head">
            <a class="back" href="{{ route('home') }}#projects"><x-icon name="arrow-left" /><span>All projects</span></a>
            <div class="dlg-meta">
                <span class="code">PRJ-{{ str_pad((string) $position, 2, '0', STR_PAD_LEFT) }}</span>
                <x-type-badge :type="$project->type" />
            </div>
            <h1 data-split>{{ $project->title }}</h1>
            <p class="lede">{{ $project->summary }}</p>
        </div>
    </div>
</header>

<main class="section">
    <div class="wrap detail-grid">
        <div class="detail-side">
            <div class="gallery" data-gallery>
                @if ($shots->isNotEmpty())
                    @php $first = $shots->first(); @endphp
                    <figure class="gallery-main{{ $first->isPortrait() ? ' is-portrait' : '' }}">
                        <img data-gallery-main src="{{ $first->url() }}" alt="{{ $first->alt ?: $project->title.' screenshot' }}">
                    </figure>
                    <p class="cap" data-gallery-caption @if (! $first->caption) hidden @endif>{{ $first->caption }}</p>
                    @if ($shots->count() > 1)
                        <div class="thumbs" role="group" aria-label="Screenshots">
                            @foreach ($shots as $i => $shot)
                                <button type="button" class="thumb" data-thumb
                                        data-src="{{ $shot->url() }}"
                                        data-alt="{{ $shot->alt ?: $project->title.' screenshot' }}"
                                        data-caption="{{ $shot->caption }}"
                                        aria-label="Show screenshot {{ $i + 1 }}"
                                        aria-pressed="{{ $i === 0 ? 'true' : 'false' }}">
                                    <img src="{{ $shot->url() }}" alt="" loading="lazy">
                                </button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="gallery-main"><x-shot-art :type="$project->type" /></div>
                @endif
            </div>

            @if ($github || $demo || $showPlaceholders)
                <div class="dsec">
                    <h2 class="label">Links</h2>
                    <div class="links">
                        @if ($github)
                            <a class="btn btn-ghost" href="{{ $github }}" target="_blank" rel="noopener noreferrer"><x-brand-icon name="github" /><span>GitHub repository</span><x-icon name="ext" /></a>
                        @elseif ($showPlaceholders)
                            <span class="btn btn-ghost is-placeholder" aria-disabled="true"><x-icon name="repo" /><span>GitHub link to be added</span></span>
                        @endif
                        @if ($demo)
                            <a class="btn btn-ghost" href="{{ $demo }}" target="_blank" rel="noopener noreferrer"><span>Live demo</span><x-icon name="ext" /></a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="detail-main">
            <section class="dsec">
                <h2 class="label">The problem</h2>
                @if ($project->problem)
                    <p>{{ $project->problem }}</p>
                @elseif ($showPlaceholders)
                    <p class="ph-box">Describe the business problem this project solves.</p>
                @endif
            </section>

            <section class="dsec">
                <h2 class="label">The solution</h2>
                @if ($project->solution)
                    <p>{{ $project->solution }}</p>
                @elseif ($showPlaceholders)
                    <p class="ph-box">Describe how this project solves the problem.</p>
                @endif
            </section>

            <section class="dsec">
                <h2 class="label">Implemented features</h2>
                @if ($features)
                    <ul class="feat feat-one">
                        @foreach ($features as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                @elseif ($showPlaceholders)
                    <p class="ph-box">List the features you implemented.</p>
                @endif
            </section>

            @if ($tech || $showPlaceholders)
                <section class="dsec">
                    <h2 class="label">Technologies used</h2>
                    <ul class="chips">
                        @forelse ($tech as $item)
                            <li><x-tech-icon :name="$item" />{{ $item }}</li>
                        @empty
                            <li class="is-placeholder">Technologies to be added</li>
                        @endforelse
                    </ul>
                </section>
            @endif

            @if ($project->contribution)
                <section class="dsec">
                    <h2 class="label">My development contribution</h2>
                    <p>{!! nl2br(e($project->contribution)) !!}</p>
                </section>
            @endif
        </div>
    </div>

    @if ($previous && $next)
        <div class="wrap pager">
            <a class="nav-btn" href="{{ route('projects.show', $previous->slug) }}">
                <x-icon name="arrow-left" />
                <span><small>Previous</small><b>{{ $previous->title }}</b></span>
            </a>
            <a class="nav-btn nav-next" href="{{ route('projects.show', $next->slug) }}">
                <span><small>Next</small><b>{{ $next->title }}</b></span>
                <x-icon name="arrow-right" />
            </a>
        </div>
    @endif
</main>

@include('partials.contact')
@endsection
