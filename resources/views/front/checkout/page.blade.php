<x-public-page title="Checkout | TasteBox" :no-robots="true">
    <div class="bg-green-600 h-2 w-full"></div>
<h1 class="type-h1 text-center my-12">Checkout</h1>
    <check-out :basket='@json($basket)' payfast-url="{{ config('payfast.sandbox', true) ? 'https://sandbox.payfast.co.za/eng/process' : 'https://www.payfast.co.za/eng/process' }}"></check-out>
</x-public-page>
