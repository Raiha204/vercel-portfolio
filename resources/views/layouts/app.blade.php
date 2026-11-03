<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', $owner['name'].' · Projects Portfolio')</title>
    <meta name="description" content="@yield('description', 'Portfolio of '.$owner['name'].': projects, certifications and contact details.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@100..125,500..800&family=Hanken+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap">
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}">

    {{-- Lets the stylesheet add scroll animations only when JavaScript is running. --}}
    <script>document.documentElement.classList.add('js');</script>
</head>
<body>
    @yield('content')

    <footer class="foot">
        <div class="wrap">
            <span>&copy; {{ date('Y') }} {{ $owner['name'] }}</span>
            <a class="to-top" href="#top">Back to top <span aria-hidden="true">&uarr;</span></a>
        </div>
    </footer>

    <script src="{{ asset('js/portfolio.js') }}" defer></script>
</body>
</html>
