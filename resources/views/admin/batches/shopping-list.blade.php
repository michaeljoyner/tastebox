<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shopping List</title>
</head>
<body style="font-family: sans-serif;">
<h1 style="text-align: center; margin-bottom: 1rem;">Shopping List for Batch #{{ $batch_number }}</h1>
<h3 style="color: #718096; text-align: center; margin-bottom: 4rem;">For delivery from {{ $delivery_date }}</h3>
@foreach($ingredients as $ingredient)
    <div style="margin-bottom: 2rem; margin-left: 3rem;">
        <p><strong>{{ $ingredient['description'] }}</strong></p>
        <div style="padding-left: 1rem;">
            @foreach($ingredient['uses'] as $use)
                <p>
                    <span>{{ $use['count'] }}</span>
                    <span> X </span>
                    <span>{{ $use['quantity'] }}</span>
                    (<small>{{ $use['meal'] }}</small>)
                </p>
            @endforeach
        </div>
    </div>

@endforeach
</body>
</html>
