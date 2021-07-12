<x-public-page title="Login">
    <div class="my-20 max-w-5xl mx-auto">
        <p class="type-h1">Welcome, please login</p>

        @if(session('status'))
            <div class="my-12 max-w-md mx-auto p-4 border border-green-700 text-green-700 rounded-xl shadow bg-green-100">
                <p class="text-sm">Congratulations, you have successfully reset your password. You may now use it to log back in.</p>
            </div>
        @endif

        <form action="/login" method="POST" class="max-w-md mx-auto px-6">
            {!! csrf_field() !!}


            <div class="my-6">
                <label class="type-b4" for=email>Your email address</label>
                @error('email')
                <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
                <input type="email" name="email" id="email" class="w-full p-2 border">
            </div>

            <div class="my-6">
                <label class="type-b4" for="password">Choose a password</label>
                @error('email')
                <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
                <input type="password" name="password" id="password" class="w-full p-2 border">
            </div>

            <button type="submit" class="block w-full py-3 bg-green-600 text-white hover:bg-green-500 rounded-lg shadow-lg my-6 font-semibold">Log In</button>
            <div>
                <a href="/forgot-password">Forgot your Password?</a>
            </div>
        </form>
    </div>
</x-public-page>
