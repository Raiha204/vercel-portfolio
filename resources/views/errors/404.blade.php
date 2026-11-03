@extends('layouts.app')

@section('title', 'Page not found · '.$owner['name'])

@section('content')
<header class="hero hero-compact">
    <div class="wrap">
        @include('partials.nav')

        <div class="detail-head">
            <p class="eyebrow">Error 404</p>
            <h1 data-split>That page does not exist.</h1>
            <p class="lede">The link may be old or mistyped. The projects, certificates and contact details are all on the home page.</p>
            <div class="hero-actions">
                <a class="btn btn-light" href="{{ route('home') }}">Back to the portfolio</a>
            </div>
        </div>
    </div>
</header>
@endsection
