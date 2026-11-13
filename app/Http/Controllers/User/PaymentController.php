<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function show(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load('payment');

        return view(
            'user.payment.show',
            compact('order')
        );
    }

    public function uploadProof(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'proof' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ]);

        $payment = $order->payment;

        abort_unless($payment, 404);

        if ($payment->status === 'verified') {
            return back()->with('error', 'Pembayaran sudah diverifikasi');
        }

        $path = $validated['proof']->store('payment-proofs', 'public');

        if ($payment->proof_path) {
            Storage::disk('public')->delete($payment->proof_path);
        }

        $payment->update([
            'proof_path' => $path,
            'status' => 'waiting_verification',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil dikirim');
    }
}
