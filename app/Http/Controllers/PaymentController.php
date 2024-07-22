<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Car;
use App\Models\CarReturn;
use App\Models\User;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

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

    public function index(Reservation $reservation, User $user, Car $car) : View {
        return view('payment.index', [
            'payments' => Payment::paginate(7),
            'reservation' => $reservation,
            'user' => $user,
            'car' => $car,
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
