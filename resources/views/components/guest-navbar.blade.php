<div class="main-nav bg-white/90 backdrop-blur-md px-6 shadow-md w-screen h-16 flex justify-between items-center fixed top-0 left-0">
    <div class="type-h4">
        <a href="/">
            @include('svg.logos.logo_new', ['classes' => 'h-8 text-green-600 hover:text-green-500'])
        </a>
    </div>
    <div class="flex">
        <div class="nav-drawer flex flex-col lg:flex-row lg:pt-0 pt-12 lg:static fixed top-16 left-0 lg:min-h-0 min-h-screen bg-green-200 lg:bg-transparent w-screen lg:w-auto items-center">
            <a class="font-bold lg:mb-0 mb-6 mx-4 block" href="/blog">Blog</a>
            <a class="font-bold lg:mb-0 mb-6 mx-4 block" href="/our-meals">Our Meals</a>
            <a class="font-bold lg:mb-0 mb-6 mx-4 block" href="/team">The Team</a>
            <a class="font-bold lg:mb-0 mb-6 mx-4 block" href="/dietician">Dietician</a>
            <a class="font-bold lg:mb-0 mb-6 mx-4 block" href="/faqs">FAQs</a>
            <a class="font-bold lg:mb-0 mb-6 mx-4 block" href="/login">Login</a>
            <a href="/build-a-box"
               class="lg:mb-0 mb-6 mx-4 green-btn">See Menus</a>
        </div>
        <div class="flex">
            <basket-bar class="mx-4"></basket-bar>
            <button class="block lg:hidden nav-trigger focus:outline-none">
                <svg class="stroke-current h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
                </svg>

            </button>
        </div>
    </div>


</div>
