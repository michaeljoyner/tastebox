<div class="py-20 px-6">
    <p class="text-3xl mb-12 text-center font-serif font-bold">On the Menu Now</p>
    <div class="max-w-6xl mx-auto" data-flickity='{"cellAlign": "left", "imagesLoaded": true, "contain": true}'>
        @foreach($current['meals'] as $meal)
            <div class="py-4 max-w-md w-full mx-4">
                <div class="w-full p-3 rounded-lg bg-green-300 shadow-lg">
                    <img src="{{ $meal['title_image'] }}" class="rounded-t-lg w-full"
                         alt="{{ $meal['name'] }}">
                    <p class="type-b4 rounded-b-lg py-2 px-2 w-full truncate bg-white">{{ $meal['name'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex justify-center mt-20">
        <a href="/build-a-box"
           class="green-btn">Place your order</a>
    </div>
</div>
