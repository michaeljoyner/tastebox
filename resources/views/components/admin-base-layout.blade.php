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
    <META NAME="ROBOTS"
          CONTENT="NOINDEX, NOFOLLOW">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

        @yield('head')
        <script>
            window.currentUser = {
                name: "{{ auth()->user()->name }}",
                email: "{{ auth()->user()->email }}",
            }
        </script>
</head>
<body class="font-sans text-gray-800 h-full">
<div id="app" class="min-h-full">
    <navbar></navbar>
    {{ $slot }}
    <notification-hub></notification-hub>
</div>
<script src="{{ $javascript }}"></script>
</body>
</html>
