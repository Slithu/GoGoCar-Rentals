@component('mail::message')
# Rental Cancelled

We have accepted your cancellation of your car rental:

- **Car**: {{ $reservation->car->brand ?? 'Null' }} {{ $reservation->car->model ?? 'Null' }}
- **Rental Start Date**: {{ $reservation->start_date ?? 'Null' }}
- **Rental End Date**: {{ $reservation->end_date ?? 'Null' }}
- **Price**: {{ $reservation->total_price ?? 'Null' }} PLN

Thank you for using our application!

@endcomponent
