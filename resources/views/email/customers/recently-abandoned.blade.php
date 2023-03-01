@component('mail::message')

Hi {{ $name }}

Please finish what you started. It is just good manners.

@component('mail::button', ['url' => $restore_link])
See Order
@endcomponent

This is what you had in your basket. If you decide to resume the order, you will still have a chance to edit this.


@foreach($boxes as $index => $box)
@component('mail::panel')
**Kit #{{ $index + 1 }}**

Delivery on: {{ $box->delivery_date }}

@foreach($box->meals as $meal)
{{ $meal['meal'] }} ({{ $meal['servings'] }} servings) <br>
@endforeach

**Deliver to**: {{ $box->delivery_address }}

@endcomponent
@endforeach


@component('mail::button', ['url' => $restore_link])
See Order
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent
