<div class="min-h-screen flex items-center"
     style="background-image: url(/images/banners/thai_noodles.jpg); background-size: cover;">
    <div class="rounded-lg bg-opaque p-8 max-w-xl ml-3 mr-3 md:ml-24">
             <div class="flex items-center mb-4">
                 @include('svg.logos.logo', ['classes' => 'h-12 md:h-24 mr-4 text-gray-700'])
                 <p class="type-h0 text-gray-700 font-bold border-b-2 border-green-300">TasteBox</p>
             </div>

            <p class="type-h2 mb-8">Healthy, ready-to-cook meal kits, delivered to your door.</p>
        <div class="flex justify-between">
            <a href="/build-a-box"
               class="green-btn">Build your box</a>
            <div class="flex justify-end items-center">
                <a href="https://www.facebook.com/TasteBoxSA" class="text-gray-500 hover:text-green-600">
                    @include('svg.social.facebook', ['classes' => 'h-8 ml-2'])
                </a>
                <a href="https://www.instagram.com/TasteBoxsa/" class="text-gray-500 hover:text-green-600">
                    @include('svg.social.instagram', ['classes' => 'h-8 ml-2'])
                </a>
            </div>
        </div>


    </div>

</div>
