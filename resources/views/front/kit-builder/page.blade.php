<x-public-page title="TasteBox | Meal Kits Delivered to Your Door" :css="mix('css/front.css')">
    <div class="bg-green-600 h-2 w-full"></div>
    <kit-manager initial-kit="{{ $kit }}" :menus='@json($menus)' :initial-basket='@json($basket)'></kit-manager>
</x-public-page>
