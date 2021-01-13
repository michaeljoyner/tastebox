<div style="height: 208mm; width: 148mm;" class="">
    <div class="py-12">
        <img src="{{ url('/images/old_logo.png') }}" class="w-24 mx-auto"
             alt="">
    </div>

    <img src="{{ $meal['title_image'] }}" style="width: 100%;"
         alt="">

    <div class="px-6 pt-6">
        <p class="type-h3">{{ $meal['name'] }}</p>
        <div class="flex justify-between border-t border-gray-500 pt-2">
            <div class="space-x-2">
                @foreach($meal['classifications'] as $category)
                    <span class="border border-gray-800 rounded font-serif text-xs p-1">{{ $category['name'] }}</span>
                @endforeach
            </div>

            <div class="flex items-center">
                @include('svg.icons.clock', ['classes' => 'h-4 mr-1 text-gray-600'])
                <span class="text-xs">{{ $meal['cook_time'] + $meal['prep_time'] }} mins</span>
            </div>



        </div>
    </div>
</div>
