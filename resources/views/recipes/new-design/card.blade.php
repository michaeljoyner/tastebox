<!doctype html>
<html lang="en"
      style="width: 296mm;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>{{ $meal['name'] }}</title>
    <link rel="preconnect"
          href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Domine:wght@500&display=swap"
          rel="stylesheet">
{{--    <link rel="stylesheet"--}}
{{--          href="{{ public_path(mix('css/front.css')) }}"/>--}}
    @vite(['resources/css/front.css'])
</head>
<body style="padding: 0; margin: 0;">
<div class="flex">
    @include('recipes.new-design.card-front')
    @include('recipes.new-design.card-front')
</div>

<div class="flex">
    @include('recipes.new-design.card-back')
    @include('recipes.new-design.card-back')
</div>


</body>
</html>
