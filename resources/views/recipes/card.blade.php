<!doctype html>
<html lang="en" style="width: 148mm;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>{{ $meal['name'] }}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Domine:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet"
          href="{{ public_path(mix('css/front.css')) }}"/>
</head>
<body style="padding: 0; margin: 0;">
<div style="height: 208mm; width: 148mm;" class="">
    <div class="py-12">
        <img src="{{ url('/images/old_logo.png') }}" class="w-24 mx-auto"
             alt="">
    </div>

    <img src="{{ $meal['title_image'] }}" style="width: 100%;"
         alt="">

    <div class="px-6 pt-6">
        <p class="type-h3">{{ $meal['name'] }}</p>
        <div class="flex justify-between border-t border-gray-500 pt-2">
            <div class="space-x-2">
                @foreach($meal['classifications'] as $category)
                    <span class="border border-gray-800 rounded font-serif text-xs p-1">{{ $category['name'] }}</span>
                @endforeach
            </div>

            <div class="flex items-center">
                @include('svg.icons.clock', ['classes' => 'h-4 mr-1 text-gray-600'])
                <span class="text-xs">{{ $meal['cook_time'] + $meal['prep_time'] }} mins</span>
            </div>



        </div>
    </div>
</div>
<div style="height: 208mm; width: 148mm;" class=" px-6">
    <div class="pt-6">
        <p class="type-h3 mb-3">Ingredients:</p>
        <p class="type-b5">You will need:</p>
        <div class="text-xxs mb-4" style="">
            @foreach($meal['ingredients'] as $ingredient)
                @if(!$ingredient['in_kit'])
                    <p>{{ $ingredient['description'] }}</p>
                @endif
            @endforeach
        </div>

        <p class="type-b5">In Your kit</p>
        <div class="text-xxs" style="column-count: 2;">
            @foreach($meal['ingredients'] as $ingredient)
                @if($ingredient['in_kit'])
                <p>{{ $ingredient['description'] }}</p>
                @endif
            @endforeach
        </div>
    </div>

    <div class="pt-6">
        <p class="type-h3">Instructions:</p>
        <div class="text-xxs">
            {!! $meal['instructions'] !!}
        </div>
    </div>


</div>
</body>
</html>
