<div class="flex flex-col md:flex-row justify-between max-w-3xl mx-auto mb-12 shadow-lg rounded-lg">
    <div class="w-full md:w-48">
        <a href="/blog/{{ $post['slug'] }}">
            <img src="{{ $post['title_image']['web'] }}" class="w-full h-full object-cover"
                 alt="{{ $post['title'] }}">
        </a>
    </div>
    <div class="p-3 flex-1 flex flex-col justify-between">
        <div>
            <div class="flex">
                <p class="type-h3 text-gray-700 border-b border-green-500">
                    <a href="/blog/{{ $post['slug'] }}" class="hover:text-green-600">{{ $post['title'] }}</a>
                </p>

            </div>
            <p class="my-4">{{ $post['intro'] }}</p>
        </div>
        <p class="type-b4">{{ $post['first_published'] }}</p>
    </div>
</div>
