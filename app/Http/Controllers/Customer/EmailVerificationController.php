<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function notice(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()
                ->route('account.dashboard');
        }

        return view(
            'customer.auth.verify-email'
        );
    }


    public function verify(
        EmailVerificationRequest $request
    ): RedirectResponse {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()
                ->route('account.dashboard')
                ->with(
                    'verification_success',
                    'Your email address is already verified.'
                );
        }

        if (
            $request->user()->markEmailAsVerified()
        ) {
            event(
                new Verified(
                    $request->user()
                )
            );
        }

        return redirect()
            ->route('account.dashboard')
            ->with(
                'verification_success',
                'Your email address has been verified successfully.'
            );
    }


    public function resend(
        Request $request
    ): RedirectResponse {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()
                ->route('account.dashboard');
        }

        $request->user()
            ->sendEmailVerificationNotification();

        return back()->with(
            'verification_sent',
            'A new verification link has been sent to your email address.'
        );
    }
}