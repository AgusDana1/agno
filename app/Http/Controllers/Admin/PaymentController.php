<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with([
            'order.user',
        ])->latest()->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Request $request, Payment $payment)
    {
        if ($payment->status !== 'waiting_verification') {
            return back()->with('error', 'Pembayaran ini tidak sedang menunggu verifikasi');
        }

        DB::transaction(function () use ($request, $payment) {
            $payment->update([
                'status' => 'verified',
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
                'rejection_reason' => null,
            ]);

            $payment->order()->update([
                'status' => 'paid',
            ]);
        });

        return back()->with('success', 'Pembayaran berhasil diverifikasi');
    }

    public function reject(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        if ($payment->status !== 'waiting_verification') {
            return back()->with(
                'error',
                'Pembayaran tidak sedang menunggu verifikasi'
            );
        }

        $payment->update([
            'status' => 'rejected',
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Pembayaran kami tolak!');
    }
}
