@props(['job'])
<li class="job reveal">
    <span class="job-dot" aria-hidden="true"></span>
    <div class="job-body">
        <div class="job-head">
            <h3>{{ $job->title }}</h3>
            <p class="job-company">{{ $job->company }}@if ($job->employment_type)<span class="job-sep"> · </span>{{ $job->employment_type }}@endif</p>
        </div>
        <p class="job-meta">
            <span>{{ $job->period() }}</span><span class="job-sep"> · </span><span>{{ $job->duration() }}</span>
        </p>
        @if ($job->location || $job->work_mode)
            <p class="job-meta">{{ collect([$job->location, $job->work_mode])->filter()->implode(' · ') }}</p>
        @endif
        @if ($job->description)
            <p class="job-desc">{{ $job->description }}</p>
        @endif
    </div>
</li>
