@component('mail::message')
It's Friday, and you are looking god-damned fantastic!

Orders are closed and it's time to roll up your sleeves.

@if($pending)
**IMPORTANT** There are legitimate pending orders. Please check on them before doing the shopping, etc.
@endif

{{ $total_kits }} kits have been ordered, with a total of {{ $total_meals }} meal to be packed. A total of {{ $total_add_ons }} Add-Ons need to be accounted for. The shopping list has been attached. Snap to it.



Kind regards,<br>
Your boss
@endcomponent
