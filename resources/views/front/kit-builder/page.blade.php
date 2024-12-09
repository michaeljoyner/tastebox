<x-public-page title="Build Your Box | Meal Kits Delivered to Your Door" :css="mix('css/front.css')"  :no-robots="true">
    <div class="bg-green-600 h-2 w-full"></div>

    <kit-manager :registered="{{ auth()->user() ? 'true' : 'false' }}" initial-kit="{{ $kit }}" initial-view="{{ $showView }}" :menus='@json($menus)' :initial-basket='@json($basket)'></kit-manager>
</x-public-page>
