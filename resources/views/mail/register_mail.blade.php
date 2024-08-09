@component('mail::message')
# Welcome, {{ $user->name }}!

Thank you for registering with our application. We're excited to have you on board.

## Registration Details

- **Name**: {{ $user->name ?? 'Null' }}
- **Surname**: {{ $user->surname ?? 'Null' }}
- **Email**: {{ $user->email ?? 'Null' }}
- **Registration Date**: {{ \Carbon\Carbon::now()->addHour(2)->format('d-m-Y H:i') }}

@component('mail::button', ['url' => route('login')])
Log In
@endcomponent

Thank you for choosing us!

Best regards,
{{ config('app.name') }}

@endcomponent
