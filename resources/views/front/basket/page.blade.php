<x-public-page title="My Basket" :css="mix('css/front.css')" :no-robots="true">
    <div class="bg-green-600 h-2 w-full"></div>
    <basket-page :initial-basket='@json($basket)'></basket-page>
</x-public-page>
