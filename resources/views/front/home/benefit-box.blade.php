<div class="w-64 m-6 shadow-lg p-4 flex flex-col items-center bg-white rounded-lg">
    <div class="flex items-center">
        @include("svg.icons.{$icon}", ['classes' => 'text-green-600 h-5'])
        <p class="font-bold ml-4">{{ $title }}</p>
    </div>

    <p class="text-center text-gray-600 mt-6 text-sm">{{ $text }}</p>
</div>
