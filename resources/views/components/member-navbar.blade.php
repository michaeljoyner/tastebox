<div class="main-nav bg-white px-6 shadow w-screen h-16 flex justify-between items-center fixed top-0 left-0">
    <div class="type-h4">
        <a href="/me/home">
            @include('svg.logos.logo_small', ['classes' => 'h-8 text-green-600 hover:text-green-500'])
        </a>
    </div>
    <div class="flex">
        <div class="nav-drawer flex flex-col lg:flex-row lg:pt-0 pt-12 lg:static fixed top-16 left-0 lg:min-h-0 min-h-screen bg-green-200 lg:bg-white w-screen lg:w-auto items-start lg:items-center">
            <a class="text-lg font-bold lg:mb-0 mb-6 mx-4 block" href="/me/recipes">My Recipes</a>
            <a class="text-lg font-bold lg:mb-0 mb-6 mx-4 block" href="/me/orders">Orders</a>
            <a class="text-lg font-bold lg:mb-0 mb-6 mx-4 block" href="/blog">Blog</a>
            <a class="lg:hidden text-lg font-bold lg:mb-0 mb-6 mx-4 block" href="/me/edit-profile">Account Settings</a>

            <a href="/build-a-box"
               class="lg:mb-0 mb-6 mx-4 green-btn">See Menus</a>
            <button form="logout-form" class="lg:hidden text-lg font-bold lg:mb-0 mb-6 mx-4" href="/team">Logout</button>
        </div>
        <div class="flex">
            <basket-bar class="mx-4"></basket-bar>
            <button class="block lg:hidden nav-trigger focus:outline-none">
                <svg class="stroke-current h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
                </svg>

            </button>
        </div>
        <div class="flex items-center hidden lg:block">
            <nav-menu></nav-menu>
        </div>
    </div>

    <form
        action="/logout"
        method="POST"
        id="logout-form"
        class="hidden"
    >
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </form>

</div>

