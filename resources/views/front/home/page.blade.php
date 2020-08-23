<x-public-page title="Tastebox | Meal Kits Delivered to Your Door" :css="mix('css/front.css')">
    <div class="min-h-screen flex items-center"
         style="background-image: url(/images/banners/thai_noodles.jpg); background-size: cover;">
        <div class="rounded-lg bg-opaque p-8 max-w-lg ml-24">
            <p class="text-6xl text-gray-700 font-bold mb-6">Tastebox</p>
            <p class="text-bold text-3xl mb-8">Good food, ready to cook, delivered to your door.</p>
            <a href="/build-a-box"
               class="bg-green-600 hover:bg-green-400 text-white shadow-lg px-4 py-2 font-bold rounded-lg">Build a new
                kit</a>
        </div>

    </div>


    <div>
        <p class="text-center text-4xl my-20">Order ahead</p>
        <div class="flex">
            @foreach($menus as $menu)
                <div class="m-6 p-6 border w-80">
                    <p class="text-gray-600 uppercase text-sm">Week #{{ $menu['week_number'] }}</p>
                    <p class="font-bold mb-2">Delivered on {{ $menu['delivery_from_pretty'] }}</p>
                    <div class="flex flex-wrap">
                        @foreach($menu['meals'] as $meal)
                            <img src="{{ $meal['title_image']['thumb'] }}" alt="{{ $meal['name'] }}"
                                 class="w-32 h-auto">
                        @endforeach
                    </div>
                    <form action="/my-kits" method="POST">
                        <input type="hidden" name="menu_id" value="{{ $menu['id'] }}">
                        {!! csrf_field() !!}
                        <button class="bg-white">Build your kit</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-public-page>
