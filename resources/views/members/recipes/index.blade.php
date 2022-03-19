<x-public-page title="My Recipes">

    <div class="px-8 py-20 max-w-5xl mx-auto">

        <h1 class="type-h1 mb-12">My Recipes</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            @foreach($recipes as $recipe)
            <div class="bg-white shadow-lg rounded-lg flex flex-col overflow-hidden">
                <a href="/me/recipes/{{ $recipe['slug'] }}">
                    <img src="{{ $recipe['image'] }}"
                        class="w-full h-64 object-cover transform hover:scale-105 transition"
                        alt="">
                </a>
                <div class="p-4 text-gray-700 flex-1 flex flex-col justify-between">
                    <div class="mb-6">
                        <a href="/me/recipes/{{ $recipe['slug'] }}" class="hover:text-yellow-500">
                            <p class="type-h3 mb-4">{{ $recipe['name'] }}</p>
                        </a>

                        <p class="type-b3">{{ $recipe['description'] }}</p>
                    </div>

                    <div class="flex justify-between">
                        <div class="bg-green-500 text-white text-xs rounded-full px-4 py-1">{{ $recipe['cooking_time'] }}</div>

                        <a href="/me/recipes/{{ $recipe['slug'] }}" class="hover:text-green-600 text-sm font-semibold">View &rarr;</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</x-public-page>
