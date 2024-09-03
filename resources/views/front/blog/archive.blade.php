<x-public-page title="The TasteBox Blog Archives" description="Find all our TasteBox blog entries in one convenient place">
    <div class="py-20 max-w-3xl mx-auto px-6">
        <h1 class="type-h1 text-center">The TasteBox Blog Archives</h1>
        <p class="mt-6 max-w-xl mx-auto text-center">Looking for a TasteBox blog post from the past? The archives has all our posts, going back to the very beginning.</p>
    </div>

    <div class="px-6">
        @foreach($months as $month => $posts)
            <div class="max-w-4xl mx-auto my-12">
                <p class="type-h2 text-gray-700 underline mb-4">{{ $month }}</p>

                <div class="flex flex-col">
                    @foreach($posts as $post)
                        <a class="hover:text-blue-500 my-1" href="/blog/{{ $post['slug'] }}">{{ $post['title'] }}</a>
                    @endforeach
                </div>
            </div>

        @endforeach
    </div>
</x-public-page>
