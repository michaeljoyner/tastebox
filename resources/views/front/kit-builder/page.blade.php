<x-public-page title="Tastebox | Meal Kits Delivered to Your Door" :css="mix('css/front.css')">
    <kit-manager initial-kit="{{ $kit }}" :menus='@json($menus)' :initial-basket='@json($basket)'></kit-manager>
</x-public-page>
