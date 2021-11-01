<x-public-page title="TasteBox | Home">
    <div class="max-w-5xl mx-auto py-20 px-8">
        <h1 class="type-h1">Reset Your Password</h1>

        <form action="/me/reset-password"
              method="POST">
            {!! csrf_field() !!}
            <div class="my-12 flex flex-col md:flex-row justify-between bg-green-50 md:space-x-12 p-6 rounded-lg">
                <div class="md:max-w-sm">
                    <p class="type-h3">Your Password</p>
                    <p class="my-6">Choose a secure password at least 8 characters long.</p>
                </div>
                <div class="flex-1 md:max-w-md">
                    <x-forms.text-field name="current_password"
                                        class="my-6"
                                        label="Your current password"
                                        value=""
                                        :error="$errors->first('current_password')"
                                        type="password"
                    ></x-forms.text-field>

                    <x-forms.text-field name="password"
                                        class="my-6"
                                        label="New Password"
                                        value=""
                                        :error="$errors->first('password')"
                                        type="password"
                    ></x-forms.text-field>

                    <x-forms.text-field name="password_confirmation"
                                        class="my-6"
                                        type="password"
                                        label="Confirm your Password"
                                        value=""
                                        type="password"
                    ></x-forms.text-field>


                </div>
            </div>




            <div class="flex justify-end">
                <button class="green-btn">Reset Password</button>
            </div>
        </form>
    </div>


</x-public-page>
