<x-public-page title="TasteBox | Meal Kits Delivered to Your Door"
               :css="mix('css/front.css')"
               :has-slideshow="true">
    @include('front.home.banner')
    @include('front.home.how-it-works')
    @include('front.home.current-menu')
    @include('front.home.delivery')
    @include('front.home.instagram')
</x-public-page>
