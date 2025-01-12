@component('mail::message')

Look sharp soldier, a new order is in and we need you at your finest.


**Customer**: {{ $customer_name }} <br>
**Email**: {{ $customer_email }} <br>
**Phone**: {{ $customer_phone }} <br>
**Amount**: R{{ $amount_paid / 100 }} <br>


This is what was ordered:


@foreach($boxes as $index => $box)
@component('mail::panel')
**Kit #{{ $index + 1 }}**

Delivery From: {{ $box->delivery_date }}

Meals:

@foreach($box->meals as $meal)
{{ $meal['meal'] }} ({{ $meal['servings'] }} servings) <br>
@endforeach

@if(count($box->add_ons))
Add-Ons:

@foreach($box->add_ons as $addOn)
{{ $addOn['name'] }} x {{ $addOn['qty'] }} <br>
@endforeach
@endif

Deliver to: {{ $box->delivery_address }}
@endcomponent
@endforeach



Thanks,<br>
{{ config('app.name') }}
@endcomponent
