<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Car;
use App\Models\Review;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        return view("reservations.index", [
            'reservations' => Reservation::paginate(7),
            'users' => User::all(),
            'cars' => Car::all()
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
        $validatedData = $request->validated();
        $car = Car::findOrFail($validatedData['car_id']);

        // Konwersja dat na obiekty DateTime
        $startDate = new \DateTime($validatedData['start_date']);
        $endDate = new \DateTime($validatedData['end_date']);

        // Sprawdzenie czy data zwrotu jest wcześniejsza niż data wypożyczenia
        if ($endDate <= $startDate) {
            return back()->withErrors(['end_date' => 'End date must be later than start date.']);
        }

        // Obliczanie minimalnej daty rozpoczęcia nowej rezerwacji (zakończenie poprzedniej rezerwacji + 3 godziny)
        $lastReturnDate = Reservation::where('car_id', $car->id)
            ->where('end_date', '<', now())
            ->orderByDesc('end_date')
            ->first();

        if ($lastReturnDate) {
            $endDateTime = new \DateTime($lastReturnDate->end_date);
            $minStartDate = $endDateTime->modify('+3 hours');

            // Sprawdzenie czy data startowa jest wcześniejsza niż minStartDate
            if ($startDate < $minStartDate) {
                return back()->withErrors(['start_date' => 'Car can only be rented again after 3 hours from the last return date.']);
            }
        } else {
            // Jeśli nie ma poprzednich wynajmów, można zacząć od razu
            $minStartDate = now();
        }

        // Sprawdzenie czy data startowa jest wcześniejsza niż minStartDate
        if ($startDate < $minStartDate) {
            return back()->withErrors(['start_date' => 'Start date must be at least 3 hours from now.']);
        }

        // Sprawdzenie czy data zwrotu jest wcześniejsza niż dzisiaj
        if ($endDate < now()) {
            return back()->withErrors(['end_date' => 'End date cannot be in the past.']);
        }

        // Minimalny czas wynajmu to 1 godzina
        $diffTimeInSeconds = $endDate->getTimestamp() - $startDate->getTimestamp();
        $diffHours = $diffTimeInSeconds / 3600; // Różnica w godzinach

        if ($diffHours < 1) {
            return back()->withErrors(['end_date' => 'Minimum rental time is 1 hour.']);
        }

        // Obliczenie daty minimalnej do sprawdzenia dostępności
        $checkStartDate = clone $startDate;
        $checkStartDate->modify('-2 hours');

        // Sprawdzenie dostępności samochodu w podanym przedziale czasowym
        $existingReservations = Reservation::where('car_id', $car->id)
        ->where(function ($query) use ($checkStartDate, $endDate, $startDate) {
            $query->whereBetween('start_date', [$checkStartDate, $endDate])
                ->orWhereBetween('end_date', [$checkStartDate, $endDate])
                ->orWhere(function ($query) use ($checkStartDate, $endDate, $startDate) {
                    $query->where('start_date', '<', $checkStartDate)
                        ->where('end_date', '>', $endDate);
                })
                // Dodanie warunku dla dokładnie tego samego przedziału czasowego
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '=', $startDate)
                        ->where('end_date', '=', $endDate);
                });
        })
        ->get();

        if (!$existingReservations->isEmpty()) {
            return back()->withErrors(['car_id' => 'This car is not available in the selected date range.']);
        }

        // Obliczenie liczby dni i całkowitej ceny
        $diffDays = ceil($diffTimeInSeconds / (60 * 60 * 24));
        $totalPrice = $diffDays * $car->price;

        // Zapisanie rezerwacji
        $reservation = new Reservation([
            'user_id' => $validatedData['user_id'],
            'car_id' => $validatedData['car_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_price' => $totalPrice,
            'status' => $validatedData['status']
        ]);

        $reservation->save();

        // Aktualizacja dostępności samochodów po zapisaniu rezerwacji
        Artisan::call('update:car-availability');

        return redirect(route('reservations.session'))->with('status', 'Reservation stored!');
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
    /*
    public function update(StoreReservationRequest $request, Reservation $reservation) : RedirectResponse
    {
        $reservation->fill($request->validated());
        $reservation->save();

        $user = Auth::user();
        $car = Car::findOrFail($reservation['car_id']);

        $startDate = new \DateTime($reservation['start_date']);
        $endDate = new \DateTime($reservation['end_date']);
        $diffTime = $endDate->getTimestamp() - $startDate->getTimestamp();
        $diffDays = ceil($diffTime / (60 * 60 * 24));

        $totalPrice = $diffDays * $car->price;

        $reservation = new Reservation([
            'user_id' => $reservation['user_id'],
            'car_id' => $reservation['car_id'],
            'start_date' => $reservation['start_date'],
            'end_date' => $reservation['end_date'],
            'total_price' => $totalPrice,
            'status' => $reservation['status']
        ]);

        $reservation->save();

        if ($user->role === UserRole::USER) {
            return redirect()->route('reservations.session')->with('status', 'Reservation updated!');
        }

        return redirect(route('reservations.index'))->with('status', 'Reservation updated!');
    }
    */
    public function update(StoreReservationRequest $request, Reservation $reservation): RedirectResponse
    {
        $validatedData = $request->validated();

        // Pobranie oryginalnego samochodu z rezerwacji
        $car = Car::findOrFail($reservation->car_id);

        // Pobranie daty początkowej i końcowej z formularza
        $startDate = new \DateTime($validatedData['start_date']);
        $endDate = new \DateTime($validatedData['end_date']);

        // Obliczenie różnicy czasu w sekundach
        $diffTime = $endDate->getTimestamp() - $startDate->getTimestamp();

        // Obliczenie liczby dni i godzin wynajmu
        $diffDays = floor($diffTime / (60 * 60 * 24)); // Całkowita liczba dni
        $diffHours = ceil(($diffTime % (60 * 60 * 24)) / (60 * 60)); // Reszta podzielona przez 1 godzinę

        // Obliczenie całkowitej ceny wypożyczenia na podstawie oryginalnej ceny samochodu
        $totalPrice = ($diffDays * $car->price_per_day) + ($diffHours * ($car->price_per_day / 24));

        // Aktualizacja istniejącej rezerwacji
        $reservation->fill([
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'total_price' => $totalPrice,
            'status' => $validatedData['status']
        ]);
        $reservation->save();

        // Przekierowanie na odpowiednią ścieżkę w zależności od roli użytkownika
        if (auth()->user()->role === UserRole::USER) {
            return redirect()->route('reservations.session')->with('status', 'Reservation updated!');
        }

        return redirect()->route('reservations.index')->with('status', 'Reservation updated!');
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
}
