<x-public-page title="Tastebox | Meal Kits Delivered to Your Door" :css="mix('css/front.css')">
    @include('front.home.banner')


    <div class="px-6 py-12 bg-green-400">
        <p class="text-4xl font-bold text-center mb-12">How it Works</p>
            <div class="max-w-xl mx-auto w-full shadow-lg bg-green-100 flex mb-8">
                <div class="flex justify-center items-center px-8">
                    <p class="text-5xl font-bold">1.</p>
                </div>
                <div class="p-6">
                    <p class="text-lg font-bold">Choose what you want</p>
                    <p class="mt-4">Select the meals you'd like to prepare for the week from our carefully curated menu. You should choose from 3 to 5 meals.</p>
                </div>
            </div>

        <div class="max-w-xl mx-auto w-full shadow-lg bg-green-200 flex mb-8">
            <div class="flex justify-center items-center px-8">
                <p class="text-5xl font-bold">2.</p>
            </div>
            <div class="p-6">
                <p class="text-lg font-bold">We pack and deliver</p>
                <p class="mt-4">We carefully prepare and package your meal kits, and deliver them on the Tuesday. Our boxes are designed to last for the week, and no longer.</p>
            </div>
        </div>

        <div class="max-w-xl mx-auto w-full shadow-lg bg-green-100 flex mb-8">
            <div class="flex justify-center items-center px-8">
                <p class="text-5xl font-bold">3.</p>
            </div>
            <div class="p-6">
                <p class="text-lg font-bold">You cook and enjoy</p>
                <p class="mt-4">You get to cook delicious, healthy meals without the hassle of too much chopping, prep work or shopping. Enjoy.</p>
            </div>
        </div>
    </div>
    @include('svg.wavy-divider', ['top_colour' => 'bg-green-400', 'bottom_colour' => 'text-white'])
    <div class="px-6 py-20 bg-gradient-to-b from-white to-gray-200">
        <p class="text-4xl font-bold text-center mb-12">Benefits of Tastebox</p>
        <div class="flex flex-col md:flex-row justify-center">
            @include('front.home.benefit-box', [
    'icon' => 'heart',
    'title' => 'Healthy',
    'text' => 'All our meals are designed by a registered dietitian, and have a focus on being as great for your body as they are delicious. And they are very delicious.'
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
</x-public-page>
