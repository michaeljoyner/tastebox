<div class="w-64 my-6 mx-auto md:mx-6 shadow-lg p-6 flex flex-col items-center bg-white rounded-lg">
    <div class="flex items-center">
        @include("svg.icons.{$icon}", ['classes' => 'text-green-600 h-5'])
        <p class="font-bold ml-4">{{ $title }}</p>
    </div>

    <p class="text-center text-gray-600 mt-6 text-sm">{{ $text }}</p>
</div>
