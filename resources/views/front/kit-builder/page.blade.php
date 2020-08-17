<x-public-page title="Tastebox | Meal Kits Delivered to Your Door" :css="mix('css/front.css')">
    <kit-builder :menus='@json($menus)'></kit-builder>
</x-public-page>
