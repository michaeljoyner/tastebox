<x-public-page title="The TasteBox Blog" description="Follow the latest news, opinions, recipes and ramblings from the TasteBox team">
    <div class="py-20 max-w-3xl mx-auto px-6">
        <h1 class="type-h1 text-center">The TasteBox Blog</h1>
        <p class="mt-6 max-w-xl mx-auto text-center">Whether it be quick and easy recipes, great cooking and kitchen tips, or updates on what TasteBox is up to and planning for the future, this is where you will find it.</p>
    </div>

    <div class="px-6">
        @foreach($posts as $post)
        @include('front.blog.index-card', ['post' => $post])
        @endforeach
    </div>
</x-public-page>
