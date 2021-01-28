<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shopping List for Batch #{{ $batch_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Domine:wght@500&display=swap"
          rel="stylesheet">
    <link rel="stylesheet"
          href="{{ public_path(mix('css/front.css')) }}"/>
</head>
<body class="font-sans text-sm">
<h1 class="type-h3">Shopping List for Batch #{{ $batch_number }}</h1>
<h3 class="type-b2 mb-16">For delivery from {{ $delivery_date }}</h3>
@foreach($shoppingList as $item)
    <div class="mb-6 pb-3 max-w-xl border-b border-gray-300" style="page-break-inside: avoid;">
        <p class="font-bold capitalize">{{ $item['item_name'] }}</p>
        <div class="border-b-2 border-green-600 w-full mt-1 mb-2"></div>
        <div class="flex">
            <div class="w-32">
                @foreach($item['amounts'] as $unit => $qty)
                    <p class="text-lg">
                        <span>{{ $qty }}</span>
                        <span>{{ $unit === "x_unit" ? "" : $unit }}</span>
                    </p>
                @endforeach
            </div>
            <div class="text-xs pl-6 flex-1">
                @foreach($item['uses'] as $use)
                    <p class="mb-1">{{ $use }}</p>
                @endforeach
            </div>
        </div>


    </div>

@endforeach
</body>
</html>
