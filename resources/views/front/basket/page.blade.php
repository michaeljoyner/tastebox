<x-public-page title="My Basket" :css="mix('css/front.css')">
    <basket-page :initial-basket='@json($basket)'></basket-page>
</x-public-page>
