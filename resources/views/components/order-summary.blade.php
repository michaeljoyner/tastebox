<div class="px-6 pb-20 max-w-3xl mx-auto">
    <h1 class="type-h1 my-12 text-center">Thank You!</h1>
    <div class="mb-6">
        <p>Thanks, <strong>{{ $customer }}</strong>! We value your support.</p>

        <p class="mt-4">This is what you ordered:</p>
    </div>


    @foreach($boxes as $index => $box)
        <div class="shadow p-6 my-6">
            <p class="type-h2 mb-3">Box #{{ $index + 1 }}</p>
            @foreach($box->meal_summary as $meal)
                <p>
                    <span class="mr-2">{{ $meal['name'] }}</span>
                    <span>(for {{ $meal['servings'] }})</span>
                </p>
            @endforeach

            <div class="mt-6 border-t border-gray-200 pt-4">
                <p>Delivery on <span class="type-b2">{{ $box->delivery_date->format('D, jS M') }}</span></p>
            </div>

            <p class="text-xs mt-2 uppercase text-gray-600">Deliver to:</p>
            <div>
                <p>{{ $box->line_one }}</p>
                <p>{{ $box->city }}</p>
            </div>
        </div>
    @endforeach

    @if($hasPhone())
        <p class="my-12">We will contact you on {{ $phoneNumber }} on the day of delivery to make final arrangements.</p>
    @endif

    @section('afterVue')
    <script>
        try {
            fbq("track", "Purchase", {currency: "ZAR", value: "{{ $price() }}"})
        } catch (e) {

        }
    </script>
    @endsection
</div>
