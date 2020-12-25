<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TasteBox Admin Login</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">

</head>
<body class="h-full flex justify-center items-center font-sans">
    <div class="max-w-md mx-auto w-full">
        <p class="text-2xl font-bold text-gray-800">Login to TasteBox HQ</p>
        <form action="/admin/login" method="POST">
            {!! csrf_field() !!}

            @error('email')
                <p class="my-8 p-6 rounded-lg bg-red-100 border border-red-500">Oh no you don't, you dirty scoundrel.</p>
            @enderror
            <div class="my-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-input">
            </div>

            <div class="my-6">
                <label for="email" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-input">
            </div>

            <div class="my-6">
                <button type="submit" class="btn btn-main">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
