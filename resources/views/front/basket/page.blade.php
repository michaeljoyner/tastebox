<x-public-page title="My Basket" :css="mix('css/front.css')">
    @include('svg.wavy-divider', ['top_colour' => 'bg-green-600', 'bottom_colour' => 'text-white'])
    <basket-page :initial-basket='@json($basket)'></basket-page>
</x-public-page>
