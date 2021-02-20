<div style="height: 208mm; width: 148mm;"
     class=" px-6">
    <div class="pt-6">
        <p class="type-h3 mb-3">Ingredients:</p>
        <p class="type-b5">You will need:</p>
        <div class="text-xxs mb-4"
             style="">
            @foreach($meal['required'] as $ingredient)
                <p>{{ $ingredient['description'] }}</p>
            @endforeach

        </div>

        <p class="type-b5 mb-3">In Your kit</p>
        @foreach($meal['ingredients'] as $group => $list)
            @if(strtolower($group) !== 'main')
                <div class="text-xxs mb-4">
                    <p class="font-bold uppercase">{{ $group }}:</p>
                    <div style="column-count: 2;">
                        @foreach($list as $ingredient)
                            <p>{{ $ingredient }}</p>
                        @endforeach
                    </div>

                </div>
            @endif
        @endforeach
        <div class="text-xxs"
        >
            @if(count($meal['ingredients']) > 1)
                <p class="font-bold uppercase">Main Dish:</p>
            @endif
            <div style="column-count: 2;">
                @foreach($meal['ingredients']['main'] ?? [] as $ingredient)
                    <p>{{ $ingredient }}</p>
                @endforeach
            </div>

        </div>
    </div>

    <div class="pt-6">
        <p class="type-h3">Instructions:</p>
        <div class="text-xxs">
            {!! $meal['instructions'] !!}
        </div>
    </div>


</div>
