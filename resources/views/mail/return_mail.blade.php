@component('mail::message')
# Car Return Confirmation

Your car rental with GoGoCar Rentals has been successfully returned. Here are the details:

## Return Details

- **Return Date**: {{ \Carbon\Carbon::parse($return->return_date)->format('d-m-Y H:i') }}
- **Car**: {{ $return->reservation->car->brand}} {{ $return->reservation->car->model }}

## Exterior Condition

- **Condition**: {{ $return->exterior_condition }}
@isset($return->exterior_damage_description)
- **Description**: {{ $return->exterior_damage_description }}
@endisset

## Interior Condition

- **Condition**: {{ $return->interior_condition }}
@isset($return->interior_condition_description)
- **Description**: {{ $return->interior_condition_description }}
@endisset

## Additional Information

@isset($return->car_parts_condition)
- **Car Parts Condition**: {{ $return->car_parts_condition }}
@endisset

@isset($return->comments)
- **Comments**: {{ $return->comments }}
@endisset

## Penalty

@if ($return->penalty_amount > 0)
- **Penalty Amount**: {{ number_format($return->penalty_amount, 2) }} PLN
@else
- **Penalty Amount**: None
@endif

Thank you for choosing GoGoCar Rentals!

Best regards,
{{ config('app.name') }}
@endcomponent
