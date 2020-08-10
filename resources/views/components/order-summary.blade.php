<div class="pt-20">
    <p>Thanks, {{ $customer }}. Your order is really appreciated.</p>

    <p>This is what you ordered:</p>

    @foreach($boxes as $index => $box)
    <div>
        <p>Box #{{ $index + 1 }}</p>
        @foreach($box->meal_summary as $meal)
        <p>
            <span>{{ $meal['name'] }}</span>
            <span>{{ $meal['servings'] }} servings</span>
        </p>
        @endforeach
        <p>Delivery from {{ $box->delivery_date->format('jS M, Y') }}</p>
        <p>Deliver to:</p>
        <div>
           <p>{{ $box->line_one }}</p>
           <p>{{ $box->line_two }}</p>
           <p>{{ $box->city }}</p>
           <p>{{ $box->postal_code }}</p>
        </div>
    </div>
    @endforeach
</div>
