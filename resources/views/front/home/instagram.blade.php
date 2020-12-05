<div class="py-20 px-8">
    <p class="type-h1 text-center">Follow Us</p>
    <div class="my-8 grid grid-cols-2 md:grid-cols-4 gap-1">
        @foreach($instagrams as $instagram)
            <div>
                <a rel="nofollow" target="_blank" href="{{ $instagram['permalink'] }}">
                    <img src="{{ $instagram['url'] }}"
                         alt="instagram post" class="w-full h-full object-cover">
                </a>
            </div>
        @endforeach
    </div>
</div>
