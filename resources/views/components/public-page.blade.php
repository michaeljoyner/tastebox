<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    @vite(['resources/css/front.css','resources/js/front.js'])
    <meta id="csrf-token-meta"
          name="csrf-token"
          content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Domine:wght@500&display=swap" rel="stylesheet">

    @if($hasSlideshow)
        <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    @endif

    @if($noRobots)
        <meta name="robots" content="noindex" />
    @endif


    <meta property="og:image" content="{{ $ogImage }}"/>
    <meta property="og:url" content="{{ Request::url() }}"/>
    <meta property="og:title" content="{{ $title }}"/>
    <meta property="og:site_name" content="TasteBox"/>
    <meta property="og:type" content="Website"/>
    <meta property="og:description" content="{{ $description ?? '' }}"/>
    <meta name="description" content="{{ $description ?? '' }}">
    <meta property="twitter:card" content="summary_large_image">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    @include('front.partials.ga-tracking')
    @auth()
        <script>
            window.appMember = {
                name: '{{ auth()->user()->profile?->first_name }}'
            }
        </script>
    @endauth

    @include('front.partials.fb-pixel')
    @stack('head_scripts')
</head>
<body class="font-sans text-gray-800 h-full bg-gray-50 pt-16">
<div id="app" class="min-h-full flex flex-col">
    <div class="flex-1">
        {{ $slot }}
    </div>
    <div class="bg-green-200 px-6 pt-12 pb-4">
        <div class="flex items-center justify-center">
            @include('svg.logos.logo_small', ['classes' => 'h-10 text-green-600 mr-3'])
            <p class="type-h1 text-center text-green-600">TasteBox</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 max-w-md mx-auto text-green-800 my-8">
            <a class="hover:text-green-600 mx-auto mb-4" href="/our-meals">Our Meals</a>
            <a class="hover:text-green-600 mx-auto mb-4" href="/team">The Team</a>
            <a class="hover:text-green-600 mx-auto mb-4" href="/faqs">FAQs</a>
            <a class="hover:text-green-600 mx-auto mb-4" href="/contact">Contact Us</a>
        </div>
        <p class="text-center text-green-800">&copy; {{ date('Y') }}</p>
    </div>
    <x-nav-bar></x-nav-bar>
    <toast-alerts></toast-alerts>

</div>
@yield('afterVue')
<div id="modals" class="relative z-40"></div>
@if(session()->has('toast'))
    <script>
        window.toastMessage = {
            type: '{{ session('toast')['type'] }}',
            text: '{{ session('toast')['text'] }}',
        }
    </script>
@endif
@if($hasSlideshow)
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
@endif


</body>
</html>
