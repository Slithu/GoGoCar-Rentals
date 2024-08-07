<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Car;
use App\Models\Review;
use App\Models\UserNotification;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\StoreReviewRequest;
use App\Models\CarReturn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\Log;
use App\Mail\RentalMail;
use App\Mail\RentalCancelledMail;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : View
    {
        $search = $request->input('search');

        $query = Reservation::with('carReturns', 'user', 'car');

        if ($search) {
            $searchTerms = explode(' ', $search);

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($query) use ($term) {
                        $query->whereHas('user', function ($subQuery) use ($term) {
                            $subQuery->where('name', 'like', "%{$term}%")
                                    ->orWhere('surname', 'like', "%{$term}%")
                                    ->orWhere('email', 'like', "%{$term}%");
                        })
                        ->orWhereHas('car', function ($subQuery) use ($term) {
                            $subQuery->where('brand', 'like', "%{$term}%")
                                    ->orWhere('model', 'like', "%{$term}%");
                        });
                    });
                }
            });
        }

        $reservations = $query->paginate(7);

        $users = User::all();
        $cars = Car::all();

        return view('reservations.index', [
            'reservations' => $reservations,
            'users' => $users,
            'cars' => $cars
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $carId = null) : View
    {
        return view("reservations.create", [
            'users' => User::all(),
            'cars' => Car::all(),
            'selectedCarId' => $carId
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $validatedData = $request->validated();
        $car = Car::findOrFail($validatedData['car_id']);

        // Konwersja dat na obiekty DateTime
        $startDate = new \DateTime($validatedData['start_date']);
        $endDate = new \DateTime($validatedData['end_date']);

        // Walidacja, czy data zakończenia jest późniejsza niż data rozpoczęcia
        if ($endDate <= $startDate) {
            return back()->withErrors(['end_date' => 'End date must be later than start date.']);
        }

        // Obliczenie minimalnej daty rozpoczęcia nowej rezerwacji (zakończenie poprzedniej rezerwacji + 3 godziny)
        $lastReturnDate = Reservation::where('car_id', $car->id)
            ->where('end_date', '<', now())
            ->orderByDesc('end_date')
            ->first();

        if ($lastReturnDate) {
            $endDateTime = new \DateTime($lastReturnDate->end_date);
            $minStartDate = $endDateTime->modify('+3 hours');
        } else {
            $minStartDate = new \DateTime();
        }

        // Dodanie walidacji dla minimum 1 godziny od teraz, jeśli nie było wcześniejszej rezerwacji
        if ($minStartDate <= new \DateTime()) {
            $minStartDate = (new \DateTime())->modify('+3 hour');
        }

        if ($startDate < $minStartDate) {
            return back()->withErrors(['start_date' => 'Start date must be at least 1 hour from now.']);
        }

        // Walidacja, czy data zakończenia nie jest w przeszłości
        if ($endDate < new \DateTime()) {
            return back()->withErrors(['end_date' => 'End date cannot be in the past.']);
        }

        // Walidacja, czy czas wynajmu jest co najmniej 1 godzina
        $diffTimeInSeconds = $endDate->getTimestamp() - $startDate->getTimestamp();
        $diffHours = $diffTimeInSeconds / 3600;

        if ($diffHours < 1) {
            return back()->withErrors(['end_date' => 'Minimum rental time is 1 hour.']);
        }

        // Sprawdzanie, czy samochód jest dostępny w wybranym zakresie dat
        $checkStartDate = clone $startDate;
        $checkStartDate->modify('-2 hours');

        $existingReservations = Reservation::where('car_id', $car->id)
            ->where(function ($query) use ($checkStartDate, $endDate, $startDate) {
                $query->whereBetween('start_date', [$checkStartDate, $endDate])
                    ->orWhereBetween('end_date', [$checkStartDate, $endDate])
                    ->orWhere(function ($query) use ($checkStartDate, $endDate) {
                        $query->where('start_date', '<', $checkStartDate)
                            ->where('end_date', '>', $endDate);
                    });
            })
            ->get();

        if (!$existingReservations->isEmpty()) {
            return back()->withErrors(['car_id' => 'This car is not available in the selected date range.']);
        }

        // Obliczanie całkowitego kosztu
        $diffDays = ceil($diffTimeInSeconds / (60 * 60 * 24));
        $totalPrice = $diffDays * $car->price;

        // Tworzenie nowej rezerwacji
        $reservation = new Reservation([
            'user_id' => $validatedData['user_id'],
            'car_id' => $validatedData['car_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_price' => $totalPrice,
            'status' => $validatedData['status']
        ]);

        $reservation->save();

        // Tworzenie powiadomień dla użytkownika i administratora
        UserNotification::create([
            'user_id' => $user->id,
            'title' => "New rental completed!",
            'message' => "{$reservation->user->name} {$reservation->user->surname}\nCar: {$reservation->car->brand} {$reservation->car->model}\nRental Date: {$reservation->start_date->format('Y-m-d H:i:s')} --- {$reservation->end_date->format('Y-m-d H:i:s')}\nTotal Price: {$reservation->total_price} PLN",
            'type' => 'rental',
            'status' => 'unread',
        ]);

        AdminNotification::create([
            'user_id' => 1,
            'title' => "New rental completed!",
            'message' => "Rental ID: {$reservation->id}\nUser ID: {$reservation->user->id}\nUser: {$reservation->user->name} {$reservation->user->surname}\nCar ID: {$reservation->car->id}\nCar: {$reservation->car->brand} {$reservation->car->model}\nRental Date: {$reservation->start_date->format('Y-m-d H:i:s')} --- {$reservation->end_date->format('Y-m-d H:i:s')}\nTotal Price: {$reservation->total_price} PLN",
            'type' => 'rental',
            'status' => 'unread',
        ]);

        // Wysyłanie e-maila do użytkownika
        $user = User::findOrFail($reservation->user_id);
        Mail::to($user->email)->send(new RentalMail($reservation));

        // Aktualizacja dostępności samochodów
        Artisan::call('update:car-availability');

        return redirect()->route('payment.payment', ['reservation' => $reservation->id])->with('status', 'Reservation stored successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation) : View
    {

        return view("reservations.show", [
            'reservations' => $reservation,
            'users' => User::all(),
            'cars' => Car::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation) : View
    {
        $lastSelectedValue = $reservation->status;

        return view('reservations.edit', [
            'reservations' => $reservation,
            'users' => User::all(),
            'cars' => Car::all(),
            'lastSelectedValue' => $lastSelectedValue
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreReservationRequest $request, Reservation $reservation): RedirectResponse
    {
        $validatedData = $request->validated();

        try {
            $car = Car::findOrFail($validatedData['car_id']);
            $startDate = new \DateTime($validatedData['start_date']);
            $endDate = new \DateTime($validatedData['end_date']);
            $diffTime = $endDate->getTimestamp() - $startDate->getTimestamp();
            $diffDays = ceil($diffTime / (60 * 60 * 24));
            $totalPrice = $diffDays * $car->price;

            $reservation->start_date = $validatedData['start_date'];
            $reservation->end_date = $validatedData['end_date'];
            $reservation->car_id = $validatedData['car_id'];
            $reservation->total_price = $totalPrice;
            $reservation->status = $validatedData['status'];

            $reservation->save();

            Artisan::call('update:car-availability');

            return redirect()->route('reservations.index')->with('status', 'Reservation updated!');

        } catch (\Exception $e) {
            Log::error('Error updating reservation: ' . $e->getMessage());

            return back()->withInput()->withErrors(['error' => 'Failed to update reservation.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        Artisan::call('update:car-availability');

        return redirect(route('reservations.index'))->with('status', 'Reservation deleted!');
    }

    public function showReservations()
    {
        $reservations = Reservation::where('user_id', Auth::id())->paginate(7);

        return view('reservations.session', compact('reservations'));
    }

    public function rate($id) : View
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return view('reservations.rate', [
                'message' => 'Reservation not found.'
            ]);
        }

        return view('reservations.rate', [
            'reservation' => $reservation,
            'car' => $reservation->car
        ]);
    }

    public function cancelReservation(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $oldStatus = $reservation->status;
        $reservation->update(['status' => 'cancelled']);

        if ($oldStatus !== 'cancelled') {
            try {
                $formattedStartDate = (new \DateTime($reservation->start_date))->format('Y-m-d H:i:s');
                $formattedEndDate = (new \DateTime($reservation->end_date))->format('Y-m-d H:i:s');

                UserNotification::create([
                    'user_id' => $reservation->user->id,
                    'title' => "Rental cancelled!",
                    'message' => "{$reservation->user->name} {$reservation->user->surname}\nCar: {$reservation->car->brand} {$reservation->car->model}\nRental Date: {$formattedStartDate} --- {$formattedEndDate}\nTotal Price: {$reservation->total_price} PLN\n\nThe reservation has been cancelled.",
                    'type' => 'cancellation',
                    'status' => 'unread',
                ]);

                AdminNotification::create([
                    'user_id' => 1,
                    'title' => "Rental cancelled!",
                    'message' => "Reservation ID: {$reservation->id}\nUser ID: {$reservation->user->id}\nUser: {$reservation->user->name} {$reservation->user->surname}\nCar ID: {$reservation->car->id}\nCar: {$reservation->car->brand} {$reservation->car->model}\nRental Date: {$formattedStartDate} --- {$formattedEndDate}\nTotal Price: {$reservation->total_price} PLN\n\nThe reservation has been cancelled.",
                    'type' => 'cancellation',
                    'status' => 'unread',
                ]);

                Mail::to($reservation->user->email)->send(new RentalCancelledMail($reservation));
                Artisan::call('update:car-availability');

            } catch (\Exception $e) {
                Log::error('Error cancelling reservation: ' . $e->getMessage());
                return back()->withErrors(['error' => 'Failed to cancel reservation.']);
            }
        }

        return redirect()->route('reservations.session')->with('success', 'Rental cancelled successfully.');
    }
}
