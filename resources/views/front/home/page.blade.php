<x-public-page title="TasteBox | Meal Kits Delivered to Your Door"
               :css="mix('css/front.css')"
               description="The fun and affordable way to cook healthy meals at home."
               :has-slideshow="true">
{{--    @include('front.partials.delivery-change-alert')--}}
    @include('front.home.banner')
    @include('front.home.how-it-works')
    @include('front.home.current-menu')
    @include('front.home.delivery')
    @include('front.home.instagram')
</x-public-page>
