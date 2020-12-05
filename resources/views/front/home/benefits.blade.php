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
