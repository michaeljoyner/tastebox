<x-public-page title="My Basket" :css="mix('css/front.css')">
    <h1 class="text-5xl text-center my-20">My Basket</h1>

    @foreach($basket['kits'] as $kit)
        <div class="max-w-2xl mx-auto my-12 p-6 border">
            <p class="font-bold">{{ $kit['name'] }}</p>
            <p>Delivery from {{ $kit['delivery_date'] }}</p>
            <p class="mb-3">{{ $kit['meals_count'] }} meal ({{ $kit['servings_count'] }} servings)</p>

            <p class="font-bold">Meals</p>
            @foreach($kit['meals'] as $meal)
                <p>{{ $meal['name'] }} ({{ $meal['servings'] }} people)</p>
            @endforeach
        </div>
    @endforeach
</x-public-page>
