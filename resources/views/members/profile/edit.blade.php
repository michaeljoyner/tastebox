<x-public-page title="TasteBox | Home">
    <div class="max-w-5xl mx-auto py-20 px-8">
        <h1 class="type-h1">Your Membership Settings</h1>

        <form action="/me/profile"
              method="POST">
            {!! csrf_field() !!}
            <div class="my-12 flex flex-col md:flex-row justify-between bg-white md:shadow-lg md:space-x-12 md:px-6 py-6 rounded-lg">
                <div class="md:max-w-sm">
                    <p class="type-h3">Personal Details</p>
                    <p class="my-6">This let's us know how to address you, and how to contact you if we need to.</p>
                </div>
                <div class="flex-1 md:max-w-md">
                    <x-forms.text-field name="first_name"
                                        class="my-6"
                                        label="First Name"
                                        :value="old('first_name') ?? $profile['first_name']"
                                        :error="$errors->first('first_name')"
                    ></x-forms.text-field>

                    <x-forms.text-field name="last_name"
                                        class="my-6"
                                        label="Last Name"
                                        :value="old('last_name') ?? $profile['last_name']"
                                        :error="$errors->first('last_name')"
                    ></x-forms.text-field>

                    <x-forms.text-field name="email"
                                        class="my-6"
                                        type="email"
                                        label="Email address"
                                        :value="old('email') ?? $profile['email']"
                                        :error="$errors->first('email')"
                    ></x-forms.text-field>

                    <x-forms.text-field name="phone"
                                        class="my-6"
                                        label="Cell number"
                                        :value="old('phone') ?? $profile['phone']"
                                        :error="$errors->first('phone')"
                    ></x-forms.text-field>
                </div>
            </div>

            <div class="my-12 flex flex-col md:flex-row justify-between bg-white md:shadow-lg md:space-x-12 md:px-6 py-6 rounded-lg">
                <div class="md:max-w-sm">
                    <p class="type-h3">Delivery Info</p>
                    <p class="my-6">Set your delivery address here, and then you don't need to worry about at checkout.</p>
                </div>
                <div class="flex-1 md:max-w-md">
                    <x-forms.text-field name="address_line_one"
                                        class="my-6"
                                        label="Delivery Address"
                                        :value="old('address_line_one') ?? $profile['address_line_one']"
                                        :error="$errors->first('address_line_one')"
                    ></x-forms.text-field>

{{--                    <x-forms.text-field name="address_line_two"--}}
{{--                                        class="my-6"--}}
{{--                                        label="Address line two"--}}
{{--                                        :value="old('address_line_two') ?? $profile['address_line_two']"--}}
{{--                                        :error="$errors->first('address_line_two')"--}}
{{--                    ></x-forms.text-field>--}}

                    <div class="my-6">
                        <label>
                            <span class="type-b4">Delivery Area</span>
                            @error('address_city')
                            <span class="type-b3 text-red-500">{{ $message }}</span>
                            @enderror

                            <select name="address_city"
                                    class="block w-full mt-1">
                                @foreach($available_delivery_areas as $value => $city)
                                    <option @if((old('address_city') ?? $profile['address_city']) === $city) selected
                                            @endif value="{{ $value }}">{{ $city }}</option>
                                @endforeach

                            </select>
                        </label>
                    </div>

                </div>
            </div>

            <div class="my-12 flex flex-col md:flex-row justify-between bg-white md:shadow-lg md:space-x-12 md:px-6 py-6 rounded-lg">
                <div class="md:max-w-sm">
                    <p class="type-h3">Order Reminders</p>
                    <p class="my-6 text-gray-600 type-b1">We can send you a helpful reminder to place your order before cutoff on Thursdays, if you haven't done so already. </p>
                </div>
                <div class="flex-1 md:max-w-md flex justify-center items-center pt-8 md:pt-0">
                    <div class="flex flex-col items-end space-y-6">
                        <div>
                            <label for="sms_reminders"
                                   class="flex items-center space-x-3">
                                <span class="type-h4">SMS</span>
                                <div class="h-4 bg-gray-500 rounded-full w-8 relative">
                                    <input @if(old('sms_reminders') ?? $profile['sms_reminders']) checked
                                           @endif type="checkbox"
                                           class="peer sr-only"
                                           name="sms_reminders"
                                           value="1"
                                           id="sms_reminders">
                                    <div class="w-6 h-6 bg-gray-300 rounded-full bg-green absolute -right-1 -top-1 transition peer-checked:bg-green-500 transform peer-checked:-translate-x-4"></div>
                                </div>
                            </label>
                        </div>

                        <div>
                            <label for="email_reminders"
                                   class="flex items-center space-x-3">
                                <span class="type-h4">Email</span>
                                <div class="h-4 bg-gray-500 rounded-full w-8 relative">
                                    <input @if(old('email_reminders') ?? $profile['email_reminders']) checked
                                           @endif type="checkbox"
                                           class="peer sr-only"
                                           name="email_reminders"
                                           value="1"
                                           id="email_reminders">
                                    <div class="w-6 h-6 bg-gray-300 rounded-full bg-green absolute -right-1 -top-1 transition peer-checked:bg-green-500 transform peer-checked:-translate-x-4"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>


            <div class="flex justify-end">
                <button class="green-btn">Update Info</button>
            </div>
        </form>
    </div>


</x-public-page>
