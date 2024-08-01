<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Car;
use App\Models\CarReturn;
use App\Models\User;
use App\Models\Payment;
use App\Models\UserNotification;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Mail\PaymentMail;
use App\Mail\PenaltyPaymentMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function showPaymentForm(Reservation $reservation)
    {
        $user = Auth::user();

        return view('payment.payment', [
            'reservation' => $reservation,
            'user' => $user,
            'total_price' => $reservation->total_price
        ]);
    }

    public function processPayment(Request $request)
    {
        $user = Auth::user();

        Stripe::setApiKey(config('stripe.STRIPE_SECRET'));

        $reservation = Reservation::findOrFail($request->input('reservation_id'));
        $user = User::findOrFail($request->input('user_id'));
        $totalPrice = $reservation->total_price;
        $stripeToken = $request->input('stripeToken');

        try {
            $charge = Charge::create([
                'amount' => $totalPrice * 100,
                'currency' => 'pln',
                'description' => 'Car rental payment',
                'source' => $stripeToken,
            ]);

            $payment = new Payment();
            $payment->reservation_id = $reservation->id;
            $payment->user_id = $user->id;
            $payment->stripe_charge_id = $charge->id;
            $payment->amount = $totalPrice;
            $payment->currency = 'PLN';
            $payment->type = 'rental';

            $payment->save();

            Log::info('Payment object:', $payment->toArray());

            UserNotification::create([
                'user_id' => $user->id,
                'title' => "New payment completed!",
                'message' => "{$payment->user->name} {$payment->user->surname}\nAmount: {$payment->amount}\nCurrency: {$payment->currency}",
                'type' => 'payment',
                'status' => 'unread',
            ]);

            AdminNotification::create([
                'user_id' => $user->id,
                'title' => "New payment completed!",
                'message' => "Payment ID: {$payment->id}\nRental ID: {$payment->reservation_id}\nUser ID: {$payment->user_id}\nUser: {$payment->user->name} {$payment->user->surname}\nAmount: {$payment->amount}\nCurrency: {$payment->currency}",
                'type' => 'payment',
                'status' => 'unread',
            ]);

            $user = User::findOrFail($payment->user_id);
            Mail::to($user->email)->send(new PaymentMail($payment));

            return redirect()->route('reservations.session')->with('status', 'Payment successful!');
            } catch (\Exception $e) {
                return back()->withErrors(['stripe' => 'Payment failed: ' . $e->getMessage()]);
        }
    }

    public function showPenaltyForm(CarReturn $carReturn)
    {
        $user = Auth::user();

        return view('payment.penalty', [
            'carReturn' => $carReturn,
            'user' => $user,
            'penalty_amount' => $carReturn->penalty_amount
        ]);
    }

    public function processPenalty(Request $request)
    {
        Stripe::setApiKey(config('stripe.STRIPE_SECRET'));

        $carReturn = CarReturn::findOrFail($request->input('car_return_id'));
        $user = $carReturn->user;
        $penaltyAmount = $request->input('penalty_amount') / 100;
        $stripeToken = $request->input('stripeToken');

        try {
            $charge = Charge::create([
                'amount' => $penaltyAmount * 100,
                'currency' => 'pln',
                'description' => 'Car return penalty payment',
                'source' => $stripeToken,
            ]);

            $payment = new Payment();
            $payment->reservation_id = $carReturn->reservation_id;
            $payment->user_id = $user->id;
            $payment->stripe_charge_id = $charge->id;
            $payment->amount = $penaltyAmount;
            $payment->currency = 'PLN';
            $payment->type = 'penalty';
            $payment->save();

            $carReturn->penalty_paid = true;
            $carReturn->save();

            UserNotification::create([
                'user_id' => $user->id,
                'title' => "New penalty payment completed!",
                'message' => "{$payment->user->name} {$payment->user->surname}\nAmount: {$payment->amount}\nCurrency: {$payment->currency}",
                'type' => 'penlaty',
                'status' => 'unread',
            ]);

            AdminNotification::create([
                'user_id' => $user->id,
                'title' => "New penalty payment completed!",
                'message' => "Payment ID: {$payment->id}\nRental ID: {$payment->reservation_id}\nUser ID: {$payment->user_id}\nUser: {$payment->user->name} {$payment->user->surname}\nAmount: {$payment->amount}\nCurrency: {$payment->currency}",
                'type' => 'penalty',
                'status' => 'unread',
            ]);

            $user = User::findOrFail($payment->user_id);
            Mail::to($user->email)->send(new PenaltyPaymentMail($payment));

            return redirect()->route('returns.user_returns')->with('status', 'Penalty payment successful!');
        } catch (\Exception $e) {
            return back()->withErrors(['stripe' => 'Payment failed: ' . $e->getMessage()]);
        }
    }

    public function showPayload($chargeId)
    {
        Stripe::setApiKey(config('stripe.STRIPE_SECRET'));

        try {
            $charge = Charge::retrieve($chargeId);

            return view('payment.payload', [
                'charge' => $charge
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['stripe' => 'Error fetching charge: ' . $e->getMessage()]);
        }
    }

    public function index(Request $request) : View
    {
        $search = $request->input('search');

        $query = Payment::query();

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
                        ->orWhereHas('reservation.car', function ($subQuery) use ($term) {
                            $subQuery->where('brand', 'like', "%{$term}%")
                                    ->orWhere('model', 'like', "%{$term}%");
                        });
                    });
                }
            });
        }

        $payments = $query->paginate(7);

        $reservations = Reservation::all();
        $users = User::all();
        $cars = Car::all();

        return view('payment.index', [
            'payments' => $payments,
            'reservations' => $reservations,
            'users' => $users,
            'cars' => $cars,
        ]);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect(route('payment.index'))->with('status', 'Payment deleted!');
    }

    public function userPayments(Payment $payment)
    {
        $userPayments = Payment::where('user_id', Auth::id())->paginate(4);

        return view('payment.user', compact('userPayments'));
    }
}
