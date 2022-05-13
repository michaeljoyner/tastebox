<x-public-page title="My Recipes">

    <div class="pb-20 md:pt-12 max-w-3xl mx-auto bg-white">


        <img src="{{ $recipe['image'] }}"
             class="w-full object-cover"
             alt="{{ $recipe['name'] }}">
        <div class="bg-gray-800 px-6 py-6 flex-col md:flex-row flex justify-between items-start md:items-center text-white">
            <p class="type-h2 mb-4">{{ $recipe['name'] }}</p>
            <div class="bg-white text-gray-800 text-xs rounded-full px-4 py-1">{{ $recipe['cooking_time'] }}</div>
        </div>

        <div class="p-8">
            <p class="type-h3 mb-6">Ingredients:</p>
            <ul>
                @foreach($recipe['ingredients'] as $ingredient)
                    <li>{{ $ingredient->pivot->quantity }} {{ $ingredient['description'] }}</li>
                @endforeach
            </ul>
        </div>

        @if($recipe['public_notes'])
            <div class="my-8 p-8">
                <p class="type-h3 mb-6">Notes:</p>
                <div class="prose">
                    {!! $recipe['public_notes'] !!}
                </div>
            </div>
        @endif

        <div class="p-8">
            <p class="type-h3 mb-6">Instructions:</p>
            <div class="prose">
                {!! $recipe['instructions'] !!}
            </div>
        </div>


    </div>
</x-public-page>
