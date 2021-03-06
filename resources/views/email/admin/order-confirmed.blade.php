@component('mail::message')

Ahoy, you scurvy scoundrel

All hands on deck! We have a new order.

**Customer**: {{ $customer_name }} <br>
**Email**: {{ $customer_email }} <br>
**Phone**: {{ $customer_phone }} <br>
**Amount**: R{{ $amount_paid / 100 }} <br>


This is what was ordered:


@foreach($boxes as $index => $box)
@component('mail::panel')
**Kit #{{ $index + 1 }}**

Delivery From: {{ $box->delivery_date }}

@foreach($box->meals as $meal)
{{ $meal['meal'] }} ({{ $meal['servings'] }} servings) <br>
@endforeach

Deliver to: {{ $box->delivery_address }}
@endcomponent
@endforeach



Thanks,<br>
{{ config('app.name') }}
@endcomponent
