<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    public function showPaymentForm(Reservation $reservation)
    {
        return view('payment.payment', [
            'reservation' => $reservation,
            'total_price' => $reservation->total_price
        ]);
    }


    public function processPayment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $reservation = Reservation::findOrFail($request->input('reservation_id'));
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
            $payment->stripe_charge_id = $charge->id;
            $payment->amount = $totalPrice;
            $payment->currency = 'PLN';
            $payment->save();

            return redirect()->route('reservations.session')->with('status', 'Payment successful!');
        } catch (\Exception $e) {
            return back()->withErrors(['stripe' => 'Payment failed: ' . $e->getMessage()]);
        }
    }
}
