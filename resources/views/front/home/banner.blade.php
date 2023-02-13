
<div class="min-h-screen relative bg-tb-green bg-opacity-80 md:bg-opacity-100 overflow-hidden bg-mobile-banner md:bg-none">
    <img src="/images/home/banner_chopping.jpg" class="hidden md:block absolute top-0 right-[70vw] h-screen"
         alt="">

    <img src="/images/home/banner_noodles.jpg" class="hidden md:block absolute top-0 left-[70vw] h-screen"
         alt="">

    <div class="relative flex flex-col w-full md:w-1/3 min-w-[350px] min-h-screen bg-tb-green bg-opacity-80 md:bg-opacity-100 mx-auto items-center pt-24">
        @include('svg.logos.logo_new', ['classes' => 'text-white opacity-50 h-20'])
        <h1 class="font-serif font-bold text-7xl text-white mt-8">TasteBox</h1>
        <p class="text-white text-xl font-serif text-center my-6">Healthy, ready-to-cook meal kits, delivered to your door, for only R95 per person.</p>

        <div class="flex items-center">
            <a href="/build-a-box" class="px-6 py-3 bg-tb-red hover:bg-tb-red-light text-white font-sans text-xs uppercase font-bold rounded-lg shadow-md">Build Your Box</a>
        </div>

        <div class="mt-6 flex justify-center items-center space-x-2">
            <a href="https://www.facebook.com/TasteBoxSA" class="text-white opacity-50 hover:opacity-100 ">
                @include('svg.social.facebook', ['classes' => 'h-6 ml-2'])
            </a>

            <a href="https://www.instagram.com/TasteBoxsa/" class="text-white opacity-50 hover:opacity-100 ">
                @include('svg.social.instagram', ['classes' => 'h-5 ml-2'])
            </a>
        </div>
    </div>
</div>

<div class="py-20 px-8">
    <div class="max-w-xl">
        <p class="font-serif text-5xl font-bold">Your first meal's on us!</p>
        <p class="my-6 text-slate-800 text-xl">Sign up now and get a 20% discount you can use on your first order. TasteBox members also get...</p>
        <ul>
            <li>Online access to your recipes and more.</li>
            <li>Easier check-out.</li>
            <li>Exclusive discounts.</li>
            <li>Loyalty bonuses.</li>
        </ul>
    </div>
</div>
