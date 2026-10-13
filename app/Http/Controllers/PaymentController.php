<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\ParkingSession;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('parkingSession.vehicle')->get();

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $parkingSessions = ParkingSession::with('vehicle')
            ->whereDoesntHave('payment')
            ->get();

        return view('payments.create', compact('parkingSessions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parking_session_id' => 'required|exists:parking_sessions,id|unique:payments,parking_session_id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_method' => 'required|in:Cash,GCash,Credit Card,Debit Card',
            'payment_status' => 'required|in:Pending,Paid,Failed',
        ]);

        Payment::create($data);

        return redirect()->route('payments.index')
            ->with('success', 'Payment added successfully.');
    }

    public function edit(Payment $payment)
    {
        $parkingSessions = ParkingSession::with('vehicle')
            ->whereDoesntHave('payment')
            ->orWhere('id', $payment->parking_session_id)
            ->get();

        return view('payments.edit', compact('payment', 'parkingSessions'));
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'parking_session_id' => 'required|exists:parking_sessions,id|unique:payments,parking_session_id,' . $payment->id,
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_method' => 'required|in:Cash,GCash,Credit Card,Debit Card',
            'payment_status' => 'required|in:Pending,Paid,Failed',
        ]);

        $payment->update($data);

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}
