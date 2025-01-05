<div class="px-6 py-12 border-t-4 border-green-500">
    <p class="type-h1 text-center mb-4">How it Works</p>
    <p class="type-h2 text-center mb-12">We chop, you cook!</p>
    @include('front.home.numbered-card', [
        'number' => 1,
        'title' => 'You Build Your Box',
        'text' => "Choose from an array of health-focused meal kits to be delivered to your door. Our meals are designed to last 5 days with a fresh menu of new meals available every week."
    ])

    @include('front.home.numbered-card', [
        'number' => 2,
        'title' => 'We Pack and Deliver',
        'text' => "We do the shopping and chopping so you don’t have to. All the ingredients are perfectly portioned to avoid waste and delivered every Monday at a time convenient to you."
    ])

    @include('front.home.numbered-card', [
        'number' => 3,
        'title' => 'You Cook and Enjoy',
        'text' => "Follow the simple recipe cards and enjoy cooking meals that taste amazing and pack a nutritional punch, all while learning our single promise - that healthy eating isn’t hard!"
    ])

    <div class="my-12 text-center">
        <a href="/build-a-box"
           class="green-btn">Give it a try</a>
    </div>
</div>
