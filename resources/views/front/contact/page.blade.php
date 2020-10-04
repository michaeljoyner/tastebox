<x-public-page title="Checkout | Tastebox">
    @include('svg.wavy-divider', ['top_colour' => 'bg-green-600', 'bottom_colour' => 'text-white'])
    <h1 class="text-5xl text-center my-12 font-bold">Contact Us</h1>
    <div>
        <p class="my-12 px-6 max-w-lg mx-auto">Feel free toi get in touch if you have any questions, ideas or special requests. We'd love to hear from you.</p>
    </div>
    <div class="px-6 max-w-lg mx-auto">
        <contact-form></contact-form>
    </div>
</x-public-page>
