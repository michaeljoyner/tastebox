<div class="px-6 py-12 border-t-4 border-green-500">
    <p class="type-h1 text-center mb-12">How it Works</p>
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
           class="green-btn">Give it a try</a>
    </div>
</div>
