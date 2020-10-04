<x-public-page title="Checkout | Tastebox">
    @include('svg.wavy-divider', ['top_colour' => 'bg-green-600', 'bottom_colour' => 'text-white'])
<h1 class="text-5xl text-center my-12 font-bold">Checkout</h1>
    <check-out :basket='@json($basket)'></check-out>
</x-public-page>
