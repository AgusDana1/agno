<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('items.product')->latest()->take(5)->get();

        return view('user.dashboard', compact('orders'));
    }
}

