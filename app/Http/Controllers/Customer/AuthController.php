<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('account.dashboard');
        }

        return view('customer.auth.login');
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors([
                    'email' => 'The provided email or password is incorrect.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()
            ->intended(route('account.dashboard'));
    }


    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('account.dashboard');
        }

        return view('customer.auth.register');
    }


    public function register(Request $request)
    {
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
                'unique:users,email',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8),
            ],
        ]);

        $user = User::create([
    'name' => $validated['name'],
    'email' => $validated['email'],
    'phone' => $validated['phone'] ?? null,
    'password' => Hash::make(
        $validated['password']
    ),
]);

event(new Registered($user));

Auth::login($user);

$request->session()->regenerate();

return redirect()
    ->route('verification.notice');
    }


    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    public function showForgotPassword()
    {
        if (Auth::check()) {
            return redirect()->route('account.dashboard');
        }

        return view(
            'customer.auth.forgot-password'
        );
    }


    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(
                'status',
                __($status)
            );
        }

        return back()
            ->withErrors([
                'email' => __($status),
            ])
            ->onlyInput('email');
    }


    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    public function showResetPassword(
        Request $request,
        string $token
    ) {
        if (Auth::check()) {
            return redirect()->route('account.dashboard');
        }

        return view(
            'customer.auth.reset-password',
            [
                'token' => $token,
                'email' => $request->query('email'),
            ]
        );
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => [
                'required',
            ],

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8),
            ],
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function (User $user, string $password) {

                $user->forceFill([
                    'password' => Hash::make(
                        $password
                    ),

                    'remember_token' =>
                        Str::random(60),

                ])->save();

                event(
                    new PasswordReset($user)
                );
            }
        );


        if ($status === Password::PASSWORD_RESET) {

            return redirect()
                ->route('login')
                ->with(
                    'status',
                    __($status)
                );
        }


        return back()
            ->withErrors([
                'email' => __($status),
            ])
            ->withInput(
                $request->only('email')
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('home');
    }
}