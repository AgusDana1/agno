<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingOrders = Order::whereIn('status', ['paid', 'processing'])->count();
        $waitingPayments = Payment::where('status', 'waiting_verification')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $recentOrders = Order::with(['user', 'payment'])->latest()->take(5)->get();

        return view('pegawai.dashboard', compact('pendingOrders', 'waitingPayments', 'shippedOrders', 'recentOrders'));
    }
}

