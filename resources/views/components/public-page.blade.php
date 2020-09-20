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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;800&display=swap" rel="stylesheet">

        </head>
<body class="font-sans text-gray-800 h-full pt-16">
<div id="app" class="min-h-full">
    {{ $slot }}
    <div class="bg-white px-6 shadow w-screen h-16 flex justify-between items-center fixed top-0 left-0">
        <div>
            <a href="/">
                <img src="/images/logo.jpg" alt="Tastebox logo" class="w-12">
            </a>
        </div>
        <basket-bar></basket-bar>
    </div>
</div>
<script src="{{ $javascript }}"></script>
</body>
</html>
