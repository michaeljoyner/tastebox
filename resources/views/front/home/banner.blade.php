
<div class="h-[660px] relative bg-tb-green bg-opacity-80 md:bg-opacity-100 overflow-hidden bg-mobile-banner md:bg-none">
    <img src="/images/home/banner_chopping.jpg" class="hidden md:block absolute top-0 right-[70vw] min-[1550px]:right-[78vw] h-[660px] min-[2000px]:hidden"
         alt="">

    <img src="/images/home/banner_noodles.jpg" class="hidden md:block absolute top-0 left-[70vw] min-[1550px]:left-[78vw] h-[660px] min-[2000px]:hidden"
         alt="">

    <div class="relative flex flex-col w-full md:w-1/3 min-w-[350px] min-h-screen bg-tb-green bg-opacity-80 md:bg-opacity-100 mx-auto items-center pt-20">
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

        <div class="mt-16">
{{--            <img src="/images/home/first_meal.png" class="max-w-[80vw] w-80 -rotate-3"--}}
{{--                 alt="">--}}
            <p class="text-2xl md:text-2xl text-white font-serif font-bold">Get your first meal on us!</p>
            <div>
                <a href="#" data-scroll-target="sign-up" class="scroll-jumper text-white hover:text-green-100 opacity-75 flex flex-col items-center -space-y-2 mt-2">
                    <span>learn how</span>
                    <span class="-mt-4">&downarrow;</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="py-20 px-8 scroll-m-12" id="sign-up">
    <div class="max-w-xl mx-auto" >
        <div class="flex flex-col items-center">
            <p class="type-h1 text-center">Sign up now for rewards!</p>
            <p class="my-6 text-slate-800 text-xl text-center">Sign up now and get a <strong>20% discount</strong> you can use on your first order. TasteBox members also get online access to their recipes, free weekly recipes, exclusive discounts and more!</p>
            <div class="mt-12">
                <a href="/register" class="green-btn">Become a member</a>
            </div>
        </div>
    </div>
</div>
