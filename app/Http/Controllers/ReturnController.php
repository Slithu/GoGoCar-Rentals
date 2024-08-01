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

    public function index(Request $request) : View
    {
        $search = $request->input('search');

        $query = CarReturn::query();

        if ($search) {
            $searchTerms = explode(' ', $search);

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($query) use ($term) {
                        $query->whereHas('reservation', function ($subQuery) use ($term) {
                            $subQuery->whereHas('user', function ($subSubQuery) use ($term) {
                                $subSubQuery->where('name', 'like', "%{$term}%")
                                            ->orWhere('surname', 'like', "%{$term}%")
                                            ->orWhere('email', 'like', "%{$term}%");
                            })
                            ->orWhereHas('car', function ($subSubQuery) use ($term) {
                                $subSubQuery->where('brand', 'like', "%{$term}%")
                                            ->orWhere('model', 'like', "%{$term}%");
                            });
                        });
                    });
                }
            });
        }

        $car_returns = $query->paginate(4);

        $reservations = Reservation::all();
        $users = User::all();
        $cars = Car::all();

        return view('returns.index', [
            'car_returns' => $car_returns,
            'reservations' => $reservations,
            'users' => $users,
            'cars' => $cars
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
