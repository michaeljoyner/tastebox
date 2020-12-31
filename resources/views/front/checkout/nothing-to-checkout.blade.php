<x-public-page title="Checkout | TasteBox"  :no-robots="true">
    <h1 class="text-5xl text-center my-12 font-bold">Checkout</h1>
    <div class="max-w-xl mx-auto px-6">
        <p>Currently you do not have anything in your shopping basket that can be checked out. You can <a href="/build-a-box" class="font-semibold text-green-600 hover:text-green-500">build a new box</a> or you can <a href="/basket" class="font-semibold text-green-600 hover:text-green-500">review your basket</a>.</p>
    </div>

    <div class="max-w-xl mx-auto flex justify-around mt-20 px-6 items-center">
        <a href="/basket" class="font-semibold text-green-600 hover:text-green-500">See your basket</a>
        <a href="/build-a-box" class="font-semibold bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded">Build a box</a>
    </div>

</x-public-page>
