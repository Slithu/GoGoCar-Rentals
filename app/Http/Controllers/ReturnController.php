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
use App\Models\AdminNotification;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReturnMail;
use Carbon\Carbon;

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

        $returnDate = Carbon::parse($return->return_date);
        $formattedDate = $returnDate->format('Y-m-d H:i:s');

        UserNotification::create([
            'user_id' => $return->user_id,
            'title' => "New car return completed!",
            'message' => "{$return->user->name} {$return->user->surname}\nCar: {$return->reservation->car->brand} {$return->reservation->car->model}\nReturn Date: {$formattedDate}\nExterior Condition: {$return->exterior_condition}\nInterior Condition: {$return->interior_condition}\nExterior Damage Description: {$return->exterior_damage_description}\nCar Parts Condition: {$return->car_parts_condition}\nPenalty Amount: {$return->penalty_amount}\nComments: {$return->comments}",
            'type' => 'return',
            'status' => 'unread',
        ]);

        AdminNotification::create([
            'user_id' => 1,
            'title' => "New car return completed!",
            'message' => "User: {$return->user->name} {$return->user->surname}\nCar ID: {$return->reservation->car_id}\nCar: {$return->reservation->car->brand} {$return->reservation->car->model}\nRental ID: {$return->reservation_id}\nReturn Date: {$formattedDate}\nExterior Condition: {$return->exterior_condition}\nInterior Condition: {$return->interior_condition}\nExterior Damage Description: {$return->exterior_damage_description}\nCar Parts Condition: {$return->car_parts_condition}\nPenalty Amount: {$return->penalty_amount}\nComments: {$return->comments}\n",
            'type' => 'return',
            'status' => 'unread',
        ]);

        $user = User::findOrFail($return->user_id);
        Mail::to($user->email)->send(new ReturnMail($return));

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
