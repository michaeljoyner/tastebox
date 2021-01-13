<div style="height: 208mm; width: 148mm;" class=" px-6">
    <div class="pt-6">
        <p class="type-h3 mb-3">Ingredients:</p>
        <p class="type-b5">You will need:</p>
        <div class="text-xxs mb-4" style="">
            @foreach($meal['ingredients'] as $ingredient)
                @if(!$ingredient['in_kit'])
                    <p>{{ $ingredient['description'] }}</p>
                @endif
            @endforeach
        </div>

        <p class="type-b5 mb-3">In Your kit</p>
        <div class="text-xxs" style="column-count: 2;">
            @foreach($meal['ingredients'] as $ingredient)
                @if($ingredient['in_kit'])
                    <p>{{ $ingredient['description'] }}</p>
                @endif
            @endforeach
        </div>
    </div>

    <div class="pt-6">
        <p class="type-h3">Instructions:</p>
        <div class="text-xxs">
            {!! $meal['instructions'] !!}
        </div>
    </div>


</div>
