<x-public-page title="Tastebox | Meal Kits Delivered to Your Door" :css="mix('css/front.css')">
    @include('front.home.banner')


    <div class="px-6 py-12 border-t-4 border-green-500 bg-purple-100">
        <p class="text-4xl font-bold text-center mb-12">How it Works</p>
        @include('front.home.numbered-card', [
            'number' => 1,
            'title' => 'Choose what your want',
            'text' => "Select the meals you'd like to prepare for the week from our carefully curated menu. You should choose from 3 to 5 meals."
        ])

        @include('front.home.numbered-card', [
            'number' => 2,
            'title' => 'We pack and deliver',
            'text' => "We carefully prepare and package your meal kits, and deliver them on the Tuesday. Our boxes are designed to last for the week, and no longer."
        ])

        @include('front.home.numbered-card', [
            'number' => 3,
            'title' => 'You cook and enjoy',
            'text' => "You get to cook delicious, healthy meals without the hassle of too much chopping, prep work or shopping. Enjoy."
        ])

        <div class="my-12 text-center">
            <a href="/build-a-box"
               class="bg-green-600 hover:bg-green-400 text-white shadow-lg px-4 py-2 font-bold rounded-lg">Give it a try</a>
        </div>
    </div>
    @include('svg.wavy-divider', ['top_colour' => 'bg-purple-100', 'bottom_colour' => 'text-green-300'])
    <div class="px-6 py-20 bg-green-300">
        <p class="text-4xl font-bold text-center mb-12">Benefits of Tastebox</p>
        <div class="flex flex-col md:flex-row justify-center">
            @include('front.home.benefit-box', [
    'icon' => 'heart',
    'title' => 'Healthy',
    'text' => 'All our meals are designed by a registered dietitian, and have a focus on being as great for your body as they are delicious.'
    ])

            @include('front.home.benefit-box', [
    'icon' => 'clock',
    'title' => 'Save Time',
    'text' => 'We do the shopping, the chopping and packing. You just need to cook and enjoy.'
    ])

            @include('front.home.benefit-box', [
    'icon' => 'globe',
    'title' => 'Discover',
    'text' => 'We find interesting and fresh recipes inspired by cuisines around the world, but designed to satisfy a local hunger.'
    ])

        </div>
    </div>
    @include('svg.wavy-divider', ['top_colour' => 'bg-green-300', 'bottom_colour' => 'text-green-600'])
    <div class="bg-green-600 py-12 px-6">
        <p class="text-4xl text-white font-bold text-center mb-12">People love Tastebox</p>
        <div>
            <div class="rounded-lg shadow-lg bg-white w-full max-w-xl mx-auto p-6">
                <div class="relative px-10">
                    <svg style="transform: scale(-1,1);" class="fill-current text-green-300 z-0 absolute top-0 left-0 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.315 3.401c-1.61 0-2.916 1.343-2.916 3 0 1.656 1.306 3 2.916 3 2.915 0 .972 5.799-2.916 5.799v1.4c6.939.001 9.658-13.199 2.916-13.199zm8.4 0c-1.609 0-2.915 1.343-2.915 3 0 1.656 1.306 3 2.915 3 2.916 0 .973 5.799-2.915 5.799v1.4c6.938.001 9.657-13.199 2.915-13.199z"/></svg>
                    <p class="text-lg text-gray-600 z-10 relative">Nobody puts things in boxes like the fine folk at tastebox. You thought Christmas time was the most fun you could have opening boxes? How about a culinary Christmas every week?</p>
                </div>
                <div class="text-right pr-12">
                    <span class="text-gray-500 italic">- Mooz Joyner</span>
                </div>
            </div>
        </div>
    </div>
</x-public-page>
