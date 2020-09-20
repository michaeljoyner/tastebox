<x-public-page title="Tastebox | Meal Kits Delivered to Your Door" :css="mix('css/front.css')">
    @include('front.home.banner')


    <div class="px-6 py-20">
        <p class="text-4xl font-bold text-center mb-12">How it Works</p>
        <div class="max-w-xl mx-auto">
            <ul class="list-disc text-2xl">
                <li class="mb-4">You tell us what you want for the week</li>
                <li class="mb-4">We pack it it up into boxes and send it to you</li>
                <li class="mb-4">You live long and prosper</li>
            </ul>
        </div>
    </div>

    <div class="px-6 py-20">
        <p class="text-4xl font-bold text-center mb-12">Benefits of Tastebox</p>
        <div class="max-w-xl mx-auto">
            <ul class="list-disc text-2xl">
                <li class="mb-4">Eat healthy & delicious food</li>
                <li class="mb-4">You save money</li>
                <li class="mb-4">We make money</li>
            </ul>
        </div>
    </div>
</x-public-page>
