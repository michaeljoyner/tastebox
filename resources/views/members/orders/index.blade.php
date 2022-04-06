<x-public-page title="TasteBox | Home">
    <div class="max-w-5xl mx-auto py-20 px-8">
        <h1 class="type-h1">Your orders</h1>

        @if(!$orders->count())
            <p class="text-gray-500 my-6">You don't have any orders on record. Take a look at <a href="/build-a-box" class="font-semibold text-green-600 hover:underline">our available menus</a> if you'd like to place an order.</p>
        @endif

        <div class="my-12">
            @foreach($orders as $order)
                <div class="p-6 rounded-lg shadow-lg my-8">
                    <p class="type-h3">{{ $order['date'] }}</p>
                    <div class="my-3 flex items-center space-x-3">
                        @if($order['is_paid'])
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5 fill-current text-green-500"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <p>R{{ $order['amount_paid'] }}</p>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5 fill-current text-red-500"
                                 viewBox="0 0 20 20"
                            >
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <p>R{{ $order['amount'] }}</p>
                        @endif
                    </div>
                    <hr class="border-b-0 5 border-gray-200 my-4">
                    @foreach($order['kits'] as $index => $kit)
                        <div class="my-4">
                            <p class="type-b4">Kit #{{ $index + 1 }}</p>
                            @foreach($kit['meals'] as $meal)
                                <p class="type-b3 text-gray-500">{{ $meal['meal'] }}</p>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>


</x-public-page>
