<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarReturn;
use App\Models\Reservation;
use App\Http\Requests\StoreReturnRequest;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    public function showReturnForm(Reservation $reservation, User $user, Car $car)
    {
        return view('returns.return', ['reservation' => $reservation, 'user' => $user, 'car' => $car]);
    }

    public function processReturn(StoreReturnRequest $request) : RedirectResponse
    {
        $return = new CarReturn($request->validated());
        $return->save();

        return redirect()->route('reservations.index')->with('status', 'Car return successfully processed.');
    }

    public function index(Reservation $reservation, User $user, Car $car) : View {
        return view('returns.index', [
            'car_returns' => CarReturn::paginate(4),
            'reservation' => $reservation,
            'user' => $user,
            'car' => $car
        ]);
    }

    public function edit(CarReturn $car_return) : View
    {
        return view('returns.edit', [
            'car_returns' => $car_return,
            'reservations' => Reservation::all(),
            'users' => User::all(),
            'cars' => Car::all(),
        ]);
    }

    public function update(StoreReturnRequest $request, CarReturn $car_return) : RedirectResponse
    {
        $car_return->fill($request->validated());
        $car_return->save();
        return redirect(route('returns.index'))->with('status', 'Car return updated!');
    }

    public function destroy(CarReturn $car_return)
    {
        $car_return->delete();

        return redirect(route('returns.index'))->with('status', 'Car return deleted!');
    }

    public function userReturns()
    {
        $carReturns = CarReturn::where('user_id', Auth::id())->paginate(4);

        return view('returns.user_returns', compact('carReturns'));
    }
}
