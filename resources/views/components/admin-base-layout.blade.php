<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    @vite(['resources/js/app.js'])
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
    <script src="https://cdn.tiny.cloud/1/tmx4dzpo4heri0p2gbw9szj69j28yzeh8r8a6h626mjg101q/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body class="font-sans text-gray-800 h-full">

<div id="app" class="h-full">

</div>
<div id="side-panels" class="relative z-40"></div>
<div id="modals" class="relative z-40"></div>
<div id="notification" class="relative z-50"></div>
</body>
</html>
