<x-public-page title="Checkout | Tastebox">
    @include('svg.wavy-divider', ['top_colour' => 'bg-green-600', 'bottom_colour' => 'text-white'])
    <h1 class="text-5xl text-center my-12 font-bold">FAQs</h1>
    <div class="px-6 max-w-xl mx-auto">
        @foreach(range(1,7) as $index)
        <div class="my-12">
            <p class="font-bold text-lg">Why is Mooz so cool?</p>
            <p class="my-4">It is a small part genetics, but mostly due to his own natural brilliance. He only hopes that a small part of his exuberant coolness rubs off onto his less fortunate siblings.</p>
        </div>
        @endforeach
    </div>
</x-public-page>
