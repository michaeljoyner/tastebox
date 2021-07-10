<x-public-page title="Sign-up for Tastebox" description="Become a member">
    <div>
        <p>Hey, want to be a Tastebox member? Here is your chance.</p>

        <form action="/register" method="POST">
            {!! csrf_field() !!}
            <div>
                <label for="name">Your name</label>
                <input type="text" name="name" id="name" class="w-full p-2 border">
            </div>

            <div>
                <label for=email>Your email</label>
                <input type="email" name="email" id="email" class="w-full p-2 border">
            </div>

            <div>
                <label for="password">Choose a password</label>
                <input type="password" name="password" id="password" class="w-full p-2 border">
            </div>

            <div>
                <label for="password_confirmation">Confirm your password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full p-2 border">
            </div>

            <button type="submit">Sign-up</button>
        </form>
    </div>
</x-public-page>
