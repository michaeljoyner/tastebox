<x-mail::message>
### Buckle up Betty, someone has reached out to you. See details below.



<x-mail::panel>
**Name**: {{ $sender }}<br>
**Email**: {{ $email ?: 'not provided' }}<br>
**Phone**: {{ $phone ?: 'not provided' }}<br>
**Location**: {{ $location ?: 'not provided' }}<br>
</x-mail::panel>

{{ $message }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
