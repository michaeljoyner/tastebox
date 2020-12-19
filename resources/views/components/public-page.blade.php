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
    <meta name="description" content="{{ $description }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Domine:wght@500&display=swap" rel="stylesheet">

    @if($hasSlideshow)
        <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    @endif

        </head>
<body class="font-sans text-gray-800 h-full pt-16">
<div id="app" class="min-h-full flex flex-col">
    <div class="flex-1">
        {{ $slot }}
    </div>
    <div class="bg-green-600 px-6 py-12">
        <p class="text-3xl text-center font-black text-white">Tastebox</p>
        <p class="text-center text-green-100">&copy; {{ date('Y') }}</p>
    </div>
    <div class="main-nav bg-white px-6 shadow w-screen h-16 flex justify-between items-center fixed top-0 left-0">
        <div class="">
            <a href="/">
                <div class="font-black rounded-tl-lg rounded-br-lg border-green-500 border-4 flex">
                    <p class="bg-green-500 pl-4 pr-1 text-green-100">Taste</p>
                    <p class="text-green-500 pr-4 pl-1 bg-white">box</p>
                </div>
            </a>
        </div>
        <div class="flex">
            <div class="nav-drawer flex flex-col lg:flex-row lg:pt-0 pt-12 lg:static fixed top-16 left-0 lg:min-h-0 min-h-screen bg-green-200 lg:bg-white w-screen lg:w-auto items-center">
                <a class="text-lg font-bold lg:mb-0 mb-6 mx-4 block" href="/our-meals">Our Meals</a>
                <a class="text-lg font-bold lg:mb-0 mb-6 mx-4 block" href="/team">The Team</a>
                <a class="text-lg font-bold lg:mb-0 mb-6 mx-4 block" href="/faqs">FAQs</a>
                <a class="text-lg font-bold lg:mb-0 mb-6 mx-4 block" href="/contact">Contact Us</a>
                <a href="/build-a-box"
                   class="lg:mb-0 mb-6 mx-4 green-btn">Order</a>
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
</body>
</html>
