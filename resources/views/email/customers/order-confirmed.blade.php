@component('mail::message')

Hi {{ $customer_name }}

Your order has been confirmed, and your payment of R{{ $amount_paid / 100 }} has been received. Thank you so much for your support, we really appreciate it.

Your ordered meal kits are below:


@foreach($boxes as $index => $box)
@component('mail::panel')
**Kit #{{ $index + 1 }}**

Delivery on: {{ $box->delivery_date }}

Meals:

@foreach($box->meals as $meal)
{{ $meal['meal'] }} ({{ $meal['servings'] }} servings) <br>
@endforeach

@if(count($box->add_ons))
Add-Ons:

@foreach($box->add_ons as $addOn)
{{ $addOn['name'] }} x  {{ $addOn['qty'] }} <br>
@endforeach
@endif


**Deliver to**: {{ $box->delivery_address }}

@endcomponent
@endforeach

We will contact you on the given phone number on day of delivery to make final arrangements.



Thanks,<br>
{{ config('app.name') }}
@endcomponent
