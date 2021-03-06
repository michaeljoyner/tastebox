@component('mail::message')

Hi {{ $customer_name }}

Thank you for placing your order with TasteBox. We have received the order, and are now just waiting for confirmation from the payment provider. This can occasionally take a while and is nothing out of the ordinary. As soon as we receive confirmation, we will notify you with the final order details.

If you have not received any confirmation from us by noon on Friday, please don't hesitate to contact us at accounts@tastebox.co.za so that we can help sort out any issues.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
