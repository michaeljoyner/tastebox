<x-public-page title="Reset Your Password">
    <div class="max-w-md mx-auto px-6 py-20">
        <p class="type-h2 text-center">Password slipped your mind?</p>
        <p class="my-4 text-gray-500 text-center">Tell us which email you used to log in and we will get you back on track.</p>

        @if(session('status'))
            <div class="my-12 max-w-md mx-auto p-4 border border-green-700 text-green-700 rounded-xl shadow bg-green-100">
                <p class="text-sm">Almost there! We have sent you an email with instructions on setting your new password. Go check your email, and we'll see you at the next step.</p>
            </div>
        @endif

        <form action="/forgot-password" method="POST" class="max-w-md mx-auto mt-12">
            {!! csrf_field() !!}
            <div class="my-6">
                <label class="type-b4" for=email>Your email address</label>
                @error('email')
                <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
                <input type="email" name="email" id="email" class="w-full p-2 border">
            </div>
            <button type="submit" class="block w-full py-3 bg-green-600 text-white hover:bg-green-500 rounded-lg shadow-lg my-6 font-semibold">Request Reset</button>
        </form>
    </div>

</x-public-page>
