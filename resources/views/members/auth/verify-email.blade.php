<x-public-page title="Verify Your Email">
    <div class="max-w-lg mx-auto py-20 px-8">
        <p class="type-h2 text-center">Please verify your email.</p>
        <div class="my-6"><strong>Hi {{ auth()->user()?->name ??  'there'}},</strong> welcome to Tastebox. To get access your account please verify your email by clicking on the link we have sent to you at {{ auth()->user()?->email ??  'your email' }}. </div>

        <div>

            <p>If you never received the link, or managed to misplace it, you may request a new verification link by clicking on the button below.</p>

            @if(session('message'))
                <div class="my-12 max-w-md mx-auto p-4 border border-green-700 text-green-700 rounded-xl shadow bg-green-100">
                    <p class="text-sm">A new verification link has been sent, please check you email.</p>
                </div>
            @endif

            <form action="/email/verification-notification" method="POSt">
                {!! csrf_field() !!}
                <button type="submit" class="block w-full py-3 bg-green-600 text-white hover:bg-green-500 rounded-lg shadow-lg my-6 font-semibold">Resend Verification Link</button>
            </form>

            <div class="max-w-lg mx-auto bg-blue-100 border border-blue-700 text-blue-700 rounded-lg p-4">
                <p class="text-sm">If you don't seem to receive the verification email, please
                    <span class="font-semibold">check your spam</span> or promotions folders.</p>
            </div>
        </div>
    </div>
</x-public-page>
