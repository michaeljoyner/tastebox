<div class="shadow-lg bg-white p-6 rounded-lg flex flex-col items-start">
    <p class="type-h2 border-b-2 border-green-600">{{ $title }}</p>
    <p class="my-4">{{ $text }}</p>
    <a href="{{ $action }}"
       class="px-6 py-2 text-sm rounded bg-green-600 hover:bg-green-500 text-white font-semibold">{{ $button }} &rarr;</a>
</div>
