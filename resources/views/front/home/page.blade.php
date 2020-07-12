<x-public-page title="Tastebox | Meal Kits Delivered to Your Door" :css="mix('css/front.css')">
    <div class="h-100 bg-red-500 flex justify-center items-center">
        <form action="/my-kits" method="POST">
            <input type="hidden" name="menu_id" value="{{ $current['id'] }}">
            {!! csrf_field() !!}
            <button class="bg-white">Build your kit</button>
        </form>
    </div>


    <div>
        <p class="text-center text-4xl my-20">Order ahead</p>
        <div class="flex">
            @foreach($menus as $menu)
                <div class="m-6 p-6 border w-80">
                    <p>{{ $menu['current_range_pretty'] }}</p>
                    <div class="flex flex-wrap">
                        @foreach($menu['meals'] as $meal)
                            <img src="{{ $meal['title_image']['thumb'] }}" alt="{{ $meal['name'] }}" class="w-32 h-auto">
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
