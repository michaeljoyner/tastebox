<x-public-page title="image">
    <div style="background-image: url(/images/banners/thai_noodles.jpg); background-size: cover;" class="w-screen h-screen flex flex-col justify-center">
        <div class="rounded-lg bg-opaque p-8 max-w-xl ml-3 mr-3 md:ml-24">
            <div class="flex items-center mb-4">
                @include('svg.logos.logo', ['classes' => 'h-12 md:h-24 mr-4 text-gray-700'])
                <p class="type-h0 text-gray-700 font-bold border-b-2 border-green-300">TasteBox</p>
            </div>

            <p class="type-h2 mb-8">Healthy, ready-to-cook meal kits, delivered to your door.</p>



        </div>
    </div>
</x-public-page>
