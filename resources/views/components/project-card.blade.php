@props(['project', 'number'])
@php
    $features = $project->featureList();
    $tech = $project->techList();
    $github = $project->githubLink();
    $demo = $project->demoLink();
    $shots = $project->screenshots->values();
    $shotCount = $shots->count();
    $placeholders = (bool) config('portfolio.show_placeholders');
    $summary = $project->cardSummary();
    $problem = $project->cardProblem();
    // Show up to three features, but only as many as fit on two lines, so nothing is cut off mid-word.
    $shownFeatures = [];
    $featureChars = 0;
    foreach ($features as $feature) {
        $added = mb_strlen($feature) + ($shownFeatures ? 3 : 0);
        if ($shownFeatures && (count($shownFeatures) >= 3 || $featureChars + $added > 76)) {
            break;
        }
        $shownFeatures[] = $feature;
        $featureChars += $added;
    }
@endphp
<li class="card reveal" id="project-{{ $project->slug }}">
    <div class="shot{{ $shotCount > 1 ? ' shot-carousel' : '' }}"@if ($shotCount > 1) data-card-carousel tabindex="0" role="group" aria-roledescription="carousel" aria-label="{{ $project->title }} screenshots"@endif>
        @if ($shotCount)
            <div class="shot-slides">
                @foreach ($shots as $i => $shot)
                    <div class="shot-slide{{ $i === 0 ? ' is-active' : '' }}" data-slide>
                        <img src="{{ $shot->url() }}" alt="{{ $shot->alt ?: $project->title.' screenshot '.($i + 1) }}" loading="lazy" decoding="async" draggable="false">
                    </div>
                @endforeach
            </div>
        @else
            <x-shot-art :type="$project->type" />
        @endif
        @if ($shotCount > 1)
            <button type="button" class="shot-nav shot-prev" data-nav data-prev aria-label="Previous screenshot of {{ $project->title }}">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 5-7 7 7 7"/></svg>
            </button>
            <button type="button" class="shot-nav shot-next" data-nav data-next aria-label="Next screenshot of {{ $project->title }}">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg>
            </button>
            <div class="shot-dots" data-nav>
                @foreach ($shots as $i => $shot)
                    <button type="button" class="shot-dot{{ $i === 0 ? ' is-on' : '' }}" data-dot="{{ $i }}" aria-label="Show screenshot {{ $i + 1 }} of {{ $shotCount }}" @if ($i === 0) aria-current="true" @endif></button>
                @endforeach
            </div>
            <span class="visually-hidden" aria-live="polite" data-live></span>
        @endif
    </div>

    <div class="card-body">
        <div class="card-meta">
            <span class="code">PRJ-{{ str_pad((string) $number, 2, '0', STR_PAD_LEFT) }}</span>
            <x-type-badge :type="$project->type" />
        </div>
        <h3>{{ $project->title }}</h3>
        <p class="desc">{{ $summary }}</p>

        <div class="mini">
            <h4 class="label">Problem solved</h4>
            @if ($problem)
                <p class="clamp">{{ $problem }}</p>
            @elseif ($placeholders)
                <p class="ph-box">Describe the business problem this project solves.</p>
            @endif
        </div>

        <div class="mini">
            <h4 class="label">Key features</h4>
            @if ($features)
                <p class="feat-line">{{ implode(' · ', $shownFeatures) }}@if (count($features) > count($shownFeatures)) <span class="more">+{{ count($features) - count($shownFeatures) }} more</span>@endif</p>
            @elseif ($placeholders)
                <p class="ph-box">List the features you implemented.</p>
            @endif
        </div>

        @if ($tech || $placeholders)
            <ul class="chips" aria-label="Technologies">
                @forelse (array_slice($tech, 0, 5) as $item)
                    <li><x-tech-icon :name="$item" />{{ $item }}</li>
                @empty
                    <li class="is-placeholder">Technologies to be added</li>
                @endforelse
                @if (count($tech) > 5)
                    <li class="chip-more">+{{ count($tech) - 5 }}</li>
                @endif
            </ul>
        @endif
    </div>

    <div class="card-foot">
        <a class="btn btn-primary" href="{{ route('projects.show', $project->slug) }}" aria-label="View details: {{ $project->title }}">View Details</a>
        @if ($github)
            <a class="btn btn-ghost" href="{{ $github }}" target="_blank" rel="noopener noreferrer"><x-brand-icon name="github" /><span>GitHub</span><x-icon name="ext" /></a>
        @elseif ($placeholders)
            <span class="btn btn-ghost is-placeholder" aria-disabled="true"><x-icon name="repo" /><span>GitHub link to be added</span></span>
        @endif
        @if ($demo)
            <a class="btn btn-ghost" href="{{ $demo }}" target="_blank" rel="noopener noreferrer"><span>Live demo</span><x-icon name="ext" /></a>
        @endif
    </div>
</li>
