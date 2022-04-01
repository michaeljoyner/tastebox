<x-public-page title="TasteBox | Home">
    <div class="max-w-5xl mx-auto py-20 px-8">
        <h1 class="type-h1 mb-8">Welcome home, {{ $profile['first_name'] }}</h1>

        @if(!$profile['is_complete'])
            <x-action-box
                title="Ahoy, let's get that profile complete."
                text="We need to get some basic info from you, so that you can check out smoothly. It won't take a minute."
                action="/me/edit-profile"
                button="Set my info"
            />

        @endif

        @if(!$has_next_order && $profile['is_complete'])
            <x-action-box
                title="Don't forget to place your order"
                text="You haven't placed an order yet for the next available menu. If you'd like, you can do that now."
                action="/build-a-box"
                button="See menus"
            />

        @endif

        @if($profile['is_complete'] || $upcoming_kits->count())
            <div class="my-12">
                <p class="type-h2">Your upcoming Kits</p>

                @if(!$upcoming_kits->count())
                    <p class="text-gray-500 my-6 max-w-xl">You have not have any kits ordered for the upcoming weeks. Take a look at our <a href="/build-a-box" class="hover:text-green-600 underline">upcoming menus</a> to see what is available.</p>
                @endif

                @foreach($upcoming_kits as $kit)
                    <div class="p-4 rounded-lg shadow-lg my-8">
                        <div class="flex items-center justify-between">
                            <p class="type-h3 text-gray-700">Menu #{{ $kit['menu_week'] }}</p>
                            <p class="text-gray-500 type-b4">{{ $kit['delivery_date'] }}</p>
                        </div>
                        <div class="flex space-x-6 my-6">
                            @foreach($kit['meals'] as $meal)
                                <div class="w-16 h-16 rounded-full overflow-hidden">
                                    <img src="{{ $meal['thumb_img'] }}"
                                         class="w-full h-full object-cover"
                                         alt="">
                                </div>
                            @endforeach
                        </div>
                        <div>
                            @foreach($kit['meal_summary'] as $ordered_meal)
                                <p class="type-b3 text-gray-600 my-1">{{ $ordered_meal['name'] }} x {{ $ordered_meal['servings'] }}</p>
                            @endforeach
                        </div>
                        <hr class="border-b-0.5 border-gray-200 my-6">
                        <p class="text-gray-600 type-b3">{{ $kit['address'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        <div>
            <p class="type-h2">Discounts</p>

            @if(!$discounts->count())
                <p class="my-6 text-gray-500">You currently do not have any discounts awarded to your account.</p>
            @else

                <p class="text-gray-500 my-6">You have the following discounts available to you when you checkout.</p>
            <div class="flex flex-wrap gap-12">

                @foreach($discounts as $discount)
                    <div class="inline-block p-6 rounded-lg shadow-lg bg-green-100">
                        <p class="text-3xl">{{ $discount->valueAsString() }}</p>
                        @if($discount->valid_until)
                            <p class="text-xs text-gray-500">Valid until {{ $discount->valid_until->format(\App\DatePresenter::PRETTY_DMY) }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            @endif
        </div>
    </div>


</x-public-page>
