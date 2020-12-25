<div class="py-20 px-6">
    <p class="type-h1 mb-4 text-center">Ready for Order</p>
    <p class="type-h2 mb-12 text-center">Delivered on Monday.</p>
    <div class="max-w-6xl mx-auto" data-flickity='{"cellAlign": "left", "imagesLoaded": true, "contain": true}'>
        @foreach($current['meals'] as $meal)
            <div class="py-4 max-w-md w-full mx-4">
                <div class="w-full p-3 rounded-lg shadow-lg">
                    <img src="{{ $meal['title_image'] }}" class="rounded-t-lg w-full"
                         alt="{{ $meal['name'] }}">
                    <p class="type-b5 rounded-b-lg py-2 px-2 w-full truncate bg-white">{{ $meal['name'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex justify-center mt-20">
        <a href="/build-a-box"
           class="green-btn">Place your order</a>
    </div>
</div>
