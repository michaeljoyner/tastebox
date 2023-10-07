<div style="height: 208mm; width: 148mm;" class="relative">
    <div class="py-4 bg-tb-green flex items-center text-white absolute left-0 top-6 px-6 rounded-tr-full rounded-br-full space-x-4 shadow-md">
        @include('svg.logos.logo_new', ['classes' => 'text-white w-8 h-8'])
        <p class="font-serif text-xl font-black">TasteBox</p>
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

        <p class="mt-6">{{ $meal['description'] }}</p>
    </div>
</div>
