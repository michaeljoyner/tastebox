<x-public-page title="FAQs | TasteBox">
    <h1 class="type-h1 text-center my-12">FAQs</h1>
    <div class="px-6 max-w-xl mx-auto">
        @foreach($faqs as $faq)
        <div class="my-12">
            <p class="type-h3">{{ $faq['question'] }}</p>
            <p class="my-4">{{ $faq['answer'] }}</p>
        </div>
        @endforeach
    </div>
</x-public-page>
