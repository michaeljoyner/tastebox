@component('mail::message')
### Welcome to Tastebox

Hi {{ $name }}, welcome.

Thank you very much for signing up. To show our appreciation we have awarded you a special discount which you may use when you place an order.

You can log into your Tastebox account to see your recipes, discounts and track any orders you may place. If you have any questions or suggestions, please let us know.



@component('mail::button', ['url' => $url])
My Tastebox Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
