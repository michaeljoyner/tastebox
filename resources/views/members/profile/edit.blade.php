<x-public-page title="TasteBox | Home">
    <div class="max-w-5xl mx-auto py-20 px-8">
        <h1>Set your general info</h1>

        <form action="/me/profile"
              method="POST">
            {!! csrf_field() !!}
            <div class="my-12 flex justify-between bg-green-50 space-x-12 p-6 rounded-lg">
                <div>
                    <p class="type-h3">Contact Info</p>
                </div>
                <div class="flex-1 max-w-md">
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

            <div class="my-12 flex justify-between bg-green-50 space-x-12 p-6 rounded-lg">
                <div>
                    <p class="type-h3">Delivery Info</p>
                </div>
                <div class="flex-1 max-w-md">
                    <x-forms.text-field name="address_line_one"
                                        class="my-6"
                                        label="Address line one"
                                        :value="old('address_line_one') ?? $profile['address_line_one']"
                                        :error="$errors->first('address_line_one')"
                    ></x-forms.text-field>

                    <x-forms.text-field name="address_line_two"
                                        class="my-6"
                                        label="Address line two"
                                        :value="old('address_line_two') ?? $profile['address_line_two']"
                                        :error="$errors->first('address_line_two')"
                    ></x-forms.text-field>

                    <div class="my-6">
                        <label>
                            <span class="type-b4">Address City</span>
                            @error('address_city')
                            <span class="type-b3 text-red-500">{{ $message }}</span>
                            @enderror

                            <select name="address_city" class="block w-full mt-1">
                                @foreach(['Ashburton','Camperdown','Cato Ridge','Dalton','Hillcrest','Hilton','Howick','Kloof','Nottingham Road','Pietermaritzburg','Pinetown','Wartburg',] as $city)
                                    <option @if((old('address_city') ?? $profile['address_city']) === $city) selected @endif value="{{ $city }}">{{ $city }}</option>
                                @endforeach

                            </select>
                        </label>
                    </div>

                </div>
            </div>


            <div class="flex justify-end">
                <button class="green-btn">Update Info</button>
            </div>
        </form>
    </div>


</x-public-page>
