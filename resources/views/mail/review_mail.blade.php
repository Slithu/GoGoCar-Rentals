@component('mail::message')
# Car Review Confirmation

A new car review has been created:

- **Car**: {{ $review->car->brand ?? 'Null' }} {{ $review->car->model ?? 'Null' }}
- **Comfort**: {{ $review->comfort_rating ?? 'Null' }}
- **Driving Experience**: {{ $review->driving_experience_rating ?? 'Null' }}
- **Fuel Efficiency**: {{ $review->fuel_efficiency_rating ?? 'Null' }}
- **Safety**: {{ $review->safety_rating ?? 'Null' }}
- **Comment**: {{ $review->comment ?? 'Null' }}

Thank you for your car review!

Thank you for using our application!

@endcomponent
