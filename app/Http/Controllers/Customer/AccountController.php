<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();

        $totalOrders = Order::query()
            ->where('user_id', $user->id)
            ->count();

        $pendingOrders = Order::query()
            ->where('user_id', $user->id)
            ->whereIn('order_status', [
                'pending',
                'confirmed',
                'shipped',
            ])
            ->count();

        return view(
            'customer.account.dashboard',
            compact(
                'user',
                'totalOrders',
                'pendingOrders'
            )
        );
    }


    public function orders(Request $request)
    {
        $orders = Order::query()
            ->where(
                'user_id',
                $request->user()->id
            )
            ->latest()
            ->paginate(10);

        return view(
            'customer.account.orders',
            compact('orders')
        );
    }


    public function orderShow(
        Request $request,
        string $orderNumber
    ) {
        $order = Order::with('items')
            ->where(
                'user_id',
                $request->user()->id
            )
            ->where(
                'order_number',
                $orderNumber
            )
            ->firstOrFail();

        return view(
            'customer.account.order-show',
            compact('order')
        );
    }


    public function profile(Request $request)
    {
        $user = $request->user();

        return view(
            'customer.account.profile',
            compact('user')
        );
    }


    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:150',

                Rule::unique(
                    'users',
                    'email'
                )->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
        ]);


        $emailChanged =
            $user->email !== $validated['email'];


        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);


        if ($emailChanged) {
            $user->forceFill([
                'email_verified_at' => null,
            ])->save();
        }


        return back()->with(
            'profile_success',
            'Your account details have been updated successfully.'
        );
    }


    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validateWithBag(
            'passwordUpdate',
            [
                'current_password' => [
                    'required',
                    'current_password',
                ],

                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8),
                ],
            ]
        );


        $user->update([
            'password' => Hash::make(
                $validated['password']
            ),
        ]);


        return back()->with(
            'password_success',
            'Your password has been changed successfully.'
        );
    }
}