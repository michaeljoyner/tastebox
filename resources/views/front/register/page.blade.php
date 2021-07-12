<x-public-page title="Sign-up for Tastebox" description="Become a member">
    <div class="my-20 max-w-5xl mx-auto">
        <p class="type-h1">Hey, want to be a Tastebox member? Here is your chance.</p>

        <form action="/register" method="POST" class="max-w-md mx-auto px-6">
            {!! csrf_field() !!}
            <div class="my-6">
                <label class="type-b4" for="name">Your name</label>
                @error('name')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
                <input type="text" name="name" id="name" class="w-full p-2 border block mt-1" value="{{ old('name') ?? '' }}">
            </div>

            <div class="my-6">
                <label class="type-b4" for=email>Your email address</label>
                @error('email')
                <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
                <input type="email" name="email" id="email" class="w-full p-2 border" value="{{ old('email') ?? '' }}">
            </div>

            <div class="my-6">
                <label class="type-b4" for="password">Choose a password</label>
                @error('email')
                <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
                <input type="password" name="password" id="password" class="w-full p-2 border">
            </div>

            <div>
                <label class="type-b4" for="password_confirmation">Confirm your password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full p-2 border">
            </div>


            <button type="submit" class="block w-full py-3 bg-green-600 text-white hover:bg-green-500 rounded-lg shadow-lg my-6 font-semibold">Sign Up</button>
        </form>
    </div>
</x-public-page>
