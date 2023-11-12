<div class="py-20 px-8 border-b-2 border-tb-green">
    <div class="flex flex-col md:flex-row max-w-5xl md:gap-20 mx-auto">
        <div class="w-full md:w-1/2">
            <p class="type-h1">Our Customers ❤️ Us...</p>
            <p class="text-gray-600 my-6 max-w-lg text-lg">because we are awesome! And also because we save them time and money while still providing a good variety of interesting and nutritious meals. But don't let us tell you, here is what some of our most loyal customers have said about us.</p>
        </div>
        <div class="w-full md:w-1/2 slideshow-wide">
            <div class="py-2 w-full bg-slate-50 rounded-lg mx-4 border shadow-md">
                <div class="h-8 px-6 mb-3 flex justify-between items-center border-b">
                    <div class="flex space-x-3 items-center">
                        <div class="w-2 h-2 bg-red-500 rounded-lg"></div>
                        <div class="w-2 h-2 bg-orange-500 rounded-lg"></div>
                        <div class="w-2 h-2 bg-emerald-500 rounded-lg"></div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-4 h-4 stroke-current text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>


                </div>
            <div class="mx-auto max-w-md" data-flickity='{"cellAlign": "left", "contain": true, "autoPlay": 4000, "wrapAround": true, "prevNextButtons": false}'>
                @foreach($testes as $testimonial)
                    <div class="py-2 w-full">

                        <p class="px-6 pb-8">{!!  $testimonial !!}</p>
                    </div>
                @endforeach
            </div>
            </div>
        </div>
    </div>
</div>
