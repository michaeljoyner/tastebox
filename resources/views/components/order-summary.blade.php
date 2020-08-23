<div class="px-6 pb-20 max-w-3xl mx-auto">
    <h1 class="text-5xl my-12 font-bold text-center">Thank You!</h1>
    <div class="mb-6">
        <p>Thanks, <strong>{{ $customer }}</strong>! Your order is really appreciated.</p>

        <p class="mt-4">This is what you ordered:</p>
    </div>


    @foreach($boxes as $index => $box)
    <div class="shadow p-6 my-6">
        <p class="font-bold mb-3">Box #{{ $index + 1 }}</p>
        @foreach($box->meal_summary as $meal)
        <p>
            <span class="mr-2">{{ $meal['name'] }}</span>
            <span>(for {{ $meal['servings'] }})</span>
        </p>
        @endforeach

        <div class="mt-6 border-t border-gray-200 pt-4">
            <p>Delivery from <strong>{{ $box->delivery_date->format('jS M, Y') }}</strong></p>
        </div>

        <p class="text-xs mt-2 uppercase text-gray-600">Deliver to:</p>
        <div>
           <p>{{ $box->line_one }}, {{ $box->line_two }}</p>
           <p>{{ $box->city }}</p>
        </div>
    </div>
    @endforeach
</div>
