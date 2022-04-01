<x-public-page title="Build Your Box | Meal Kits Delivered to Your Door" :css="mix('css/front.css')"  :no-robots="true">
    <div class="bg-green-600 h-2 w-full hidden"></div>
    @include('front.home.easter-banner', ['classes' => 'static'])

    <kit-manager initial-kit="{{ $kit }}" :menus='@json($menus)' :initial-basket='@json($basket)'></kit-manager>
</x-public-page>
