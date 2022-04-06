<x-public-page title="Thank You from TasteBox" description="" :no-robots="true">
    @push('head_scripts')
        @production
        <script>
            gtag('event', 'conversion', { 'send_to': 'AW-858669646/6NmiCJ-dnPQCEM6EuZkD', 'transaction_id': '{{ $order?->order_key }}' });
        </script>
        @endproduction
    @endpush
    <x-order-summary :order="$order"></x-order-summary>

    @auth
        <div class="my-12 flex justify-center">
            <a href="/me/home" class="btn btn-main green-btn">Back Home</a>
        </div>
    @endauth


</x-public-page>
