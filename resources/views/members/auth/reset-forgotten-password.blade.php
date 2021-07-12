<x-public-page title="Reset Your Password">
    <div class="max-w-5xl mx-auto px-6 py-20">
        <p class="type-h1">Okay, let's reset your password</p>
        <p>Choose a new password and you'll be back in in no time.</p>



        <form action="/reset-password" method="POST" class="max-w-md mx-auto mt-12">
            {!! csrf_field() !!}
            <input type="hidden" name="token" value="{{ $request['token'] ?? '' }}">
            <div class="my-6">
                <label class="type-b4" for=email>Your email address</label>
                @error('email')
                <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
                <input type="email" name="email" id="email" class="w-full p-2 border" value="{{ $request['email'] ?? '' }}">
            </div>
            <div class="my-6">
                <label class="type-b4" for="password">Your new password password</label>
                @error('password')
                <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
                <input type="password" name="password" id="password" class="w-full p-2 border">
            </div>

            <div>
                <label class="type-b4" for="password_confirmation">Confirm your password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full p-2 border">
            </div>


            <button type="submit" class="block w-full py-3 bg-green-600 text-white hover:bg-green-500 rounded-lg shadow-lg my-6 font-semibold">Reset Password</button>
        </form>
    </div>
</x-public-page>
