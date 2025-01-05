@if($current)
<div class="py-20 px-6">
    <p class="type-h1 mb-4 text-center">Order Now</p>
    <p class="type-h2 mb-12 text-center">For Delivery {{ in_array(now()->dayOfWeek, [0,5,6]) ? 'next' : 'this' }} Monday.</p>
    <p class="max-w-lg my-6 text-center mx-auto">Don't miss out on this week's menu. Order before midday on Friday to have your selection of TasteBox meals delivered on the following Monday.</p>
    <div class="max-w-6xl mx-auto" data-flickity='{"cellAlign": "left", "imagesLoaded": true, "lazyLoad": 2, "contain": true}'>
        @foreach($current['meals'] as $meal)
            <div class="py-4 max-w-md w-full mx-4">
                <div class="w-full p-3 rounded-lg shadow-lg">
                    <img data-flickity-lazyload="{{ $meal['thumb_img'] }}" class="rounded-t-lg w-full"
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
@endif
