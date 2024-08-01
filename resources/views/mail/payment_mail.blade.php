@component('mail::message')
# Payment Received

A new payment has been received:

- **Payment Type**: {{ $payment->type ?? 'Null' }}
- **Amount**: {{ $payment->amount ?? 'Null' }} PLN

Thank you for using our application!

@endcomponent
