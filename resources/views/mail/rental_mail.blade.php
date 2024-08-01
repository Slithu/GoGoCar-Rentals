@component('mail::message')
# Rental Created

A new rental has been created:

- **Car**: {{ $reservation->car->brand ?? 'Null' }} {{ $reservation->car->model ?? 'Null' }}
- **Rental Start Date**: {{ $reservation->start_date->format('Y-m-d H:i:s') ?? 'Null' }}
- **Rental End Date**: {{ $reservation->end_date->format('Y-m-d H:i:s') ?? 'Null' }}
- **Price**: {{ $reservation->total_price ?? 'Null' }} PLN

Thank you for using our application!

@endcomponent
