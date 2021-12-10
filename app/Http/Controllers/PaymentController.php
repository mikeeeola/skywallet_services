<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Keygen;

class PaymentController extends Controller
{
    public function generatePaymentId($user_id)
    {
        $payment = new Payment();
        $payment->user_id = $user_id;
        $payment->payment_id = Keygen::alphanum(7)->prefix('PAY')->generate();

        $count = Payment::where('user_id', '=', $user_id)->count();
        if ($count != 4) {
            $payment->save();
            return response()->json(['payment' => $payment, 'count' => $count]);
        } else {
            return response()->json(['message' => 'You can only created up to 4 payment id']);
        }
    }

    public function getPaymentIdByUser($user_id)
    {
        $payment = Payment::where('user_id', '=', $user_id)->get();

        if ($payment) {
            return response()->json(['payment' => $payment, 'count' => $payment->count()], 200);
        } else {
            return response()->json(['message' => 'No Payment_id for user'], 404);
        }
    }

    public function destroy($id) {
        $payment = Payment::find($id);
        if ($payment) {
            $payment->destroy($id);
            return response()->json(['message' => 'Payment Id deleted'], 200);
        } else {
            return response()->json(['message' => 'Payment_id not found'], 404);
        }
    }

    public function search($paymentId)
    {
        $payment = Payment::with(['users'])->where('payment_id', $paymentId)->get();
        if ($payment) {
            return response()->json([
                'payment' => $payment
            ], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}
