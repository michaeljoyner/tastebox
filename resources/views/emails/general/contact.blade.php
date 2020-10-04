@component('mail::message')
# Tastebox Message

From: {{ $sender_name }}

Email: {{ $sender_email  }}

Phone: {{ $phone }}

{{ $message }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
