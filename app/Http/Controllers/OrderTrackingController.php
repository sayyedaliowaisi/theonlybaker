<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    public function index(): View
    {
        return view('pages.track-order');
    }

    public function search(Request $request): View
    {
        $validated = $request->validate([
            'order_number' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        $orderNumber = trim($validated['order_number']);

        $order = Order::query()
            ->where('order_number', $orderNumber)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return view('pages.track-order', [
                'trackingError' =>
                    'We could not find this order in your account. Please check your order number.',
            ]);
        }

        return view('pages.track-order', [
            'trackedOrder' => $order,
        ]);
    }
}
