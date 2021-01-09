<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link rel="stylesheet"
          href="{{ $css }}"/>
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

    <meta property="og:image" content="{{ url('/images/sharing_image.jpg') }}"/>
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

    @include('front.partials.fb-pixel')

</head>
<body class="font-sans text-gray-800 h-full pt-16">
<div id="app" class="min-h-full flex flex-col">
    <div class="flex-1">
        {{ $slot }}
    </div>
    <div class="bg-green-800 px-6 py-12">
        <div class="flex items-center justify-center">
            @include('svg.logos.logo_small', ['classes' => 'h-10 text-green-600 mr-3'])
            <p class="type-h1 text-center text-green-600">TasteBox</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 max-w-md mx-auto text-white my-8">
            <a class="hover:text-green-400 mx-auto mb-4" href="/our-meals">Our Meals</a>
            <a class="hover:text-green-400 mx-auto mb-4" href="/team">The Team</a>
            <a class="hover:text-green-400 mx-auto mb-4" href="/faqs">FAQs</a>
            <a class="hover:text-green-400 mx-auto mb-4" href="/contact">Contact Us</a>
        </div>
        <p class="text-center text-green-100">&copy; {{ date('Y') }}</p>
    </div>
    <div class="main-nav bg-white px-6 shadow w-screen h-16 flex justify-between items-center fixed top-0 left-0">
        <div class="type-h4">
            <a href="/">
                @include('svg.logos.logo_small', ['classes' => 'h-8 text-green-600 hover:text-green-500'])
            </a>
        </div>
        <div class="flex">
            <div class="nav-drawer flex flex-col lg:flex-row lg:pt-0 pt-12 lg:static fixed top-16 left-0 lg:min-h-0 min-h-screen bg-green-200 lg:bg-white w-screen lg:w-auto items-center">
                <a class="text-lg font-bold lg:mb-0 mb-6 mx-4 block" href="/our-meals">Our Meals</a>
                <a class="text-lg font-bold lg:mb-0 mb-6 mx-4 block" href="/team">The Team</a>
                <a class="text-lg font-bold lg:mb-0 mb-6 mx-4 block" href="/faqs">FAQs</a>
                <a class="text-lg font-bold lg:mb-0 mb-6 mx-4 block" href="/contact">Contact Us</a>
                <a href="/build-a-box"
                   class="lg:mb-0 mb-6 mx-4 green-btn">See Menus</a>
            </div>
            <div class="flex">
                <basket-bar class="mx-4"></basket-bar>
                <button class="block lg:hidden nav-trigger focus:outline-none">
                    <svg class="stroke-current h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
                    </svg>

                </button>
            </div>
        </div>


    </div>
</div>
@if($hasSlideshow)
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
@endif
<script src="{{ $javascript }}"></script>
@include('front.partials.ga-tracking')
</body>
</html>
