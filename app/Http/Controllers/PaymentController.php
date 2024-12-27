<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function stripeform(String $id)
{
    $payment = Payment::find($id); // No need for `->first()`; `find` already returns a single record.
    return view('stripe-payment', compact('payment')); // Pass the variable name as a string.
}

    public function createCharge(Request $request ,$payment_id)
    {
        $payment = Payment::find($payment_id);

        $payment->load('appointment');

      
        if (!$payment) {
            return redirect()->back()->withErrors(['error' => 'Payment record not found.']);
        }
        
        if (!$request->stripeToken) {
            Notification::make()
            ->title('Error')
            ->body('Stripe token is required.')
            ->send();
            return redirect()->back()->withErrors(['error' => 'Stripe token is missing.']);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        Charge::create([
            "amount" => 1000 * 100, // Amount in cents
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Payment for Appointment",
            "receipt_email" => $request->email, // Email for receipt
            "metadata" => [
                "name" => $request->name, // Add name to metadata
                "appointment_id" => $payment->appointment_id,
            ],
        ]);

        // dd($payment->appointment->status);

        $payment->appointment->update([
            'status' => 'booked',
        ]);

        $payment->update([
            'status' => 'paid',
            'pid' => $request->stripeToken,
            'payment_method' => 'stripe',
        ]);


        // Return with success notification
        return redirect()->route('filament.admin.resources.appointemnts.index')->with('success', 'Payment successfully done!');
    }
}
