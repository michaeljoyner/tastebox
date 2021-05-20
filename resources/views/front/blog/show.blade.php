<x-public-page :title="$post['title']" :description="$post['description']" :ogImage="url($post['title_image']['sharing'])">
    <div class="pt-20 max-w-3xl mx-auto px-6">
        <h1 class="type-h1 text-center leading-snug">{{ $post['title'] }}</h1>

        <p class="text-center text-gray-500 mt-6">First published {{ $post['first_published'] }}</p>
    </div>
    <div class="my-12 max-w-3xl mx-auto blog-content px-6">
        {!! $post['body'] !!}
    </div>
</x-public-page>
