<x-public-page title="Tastebox | Meal Kits Delivered to Your Door" :css="mix('css/front.css')">
    @include('svg.wavy-divider', ['top_colour' => 'bg-green-600', 'bottom_colour' => 'text-white'])
    <kit-manager initial-kit="{{ $kit }}" :menus='@json($menus)' :initial-basket='@json($basket)'></kit-manager>
</x-public-page>
