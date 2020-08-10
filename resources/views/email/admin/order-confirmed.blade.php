@component('mail::message')

Ahoy, you scurvy scoundrel

All hands on deck! We have a new order.

Customer: {{ $customer_name }}

Email: {{ $customer_email }}

Phone: {{ $customer_phone }}

Amount: R{{ $amount_paid / 100 }}

This is what was ordered:


@foreach($boxes as $index => $box)
**Kit #{{ $index + 1 }}**

Delivery From: {{ $box->delivery_date }}

@foreach($box->meals as $meal)
{{ $meal['meal'] }} ({{ $meal['servings'] }} servings)

@endforeach
Deliver to: {{ $box->delivery_address }}

@endforeach



Thanks,<br>
{{ config('app.name') }}
@endcomponent
